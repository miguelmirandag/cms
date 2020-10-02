<?php

namespace App\Http\Controllers;

use App\AttachFile;
use App\Comment;
use App\Http\Requests;
use App\Map;
use App\Photo;
use App\RelatedTopic;
use App\Section;
use App\Topic;
use App\TopicCategory;
use App\TopicField;
use App\WebmasterSection;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;

class TopicsController extends Controller
{
    private $uploadPath = "uploads/topics/";

    // Define Default Variables

    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $webmasterId
     * @return \Illuminate\Http\Response
     */
    public function index($webmasterId)
    {
        // Check Permissions
        $data_sections_arr = explode(",", Auth::user()->permissionsGroup->data_sections);
        if (!in_array($webmasterId, $data_sections_arr)) {
            return Redirect::to(route('NoPermission'))->send();
        }
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //Webmaster Topic Details
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {

            if (@Auth::user()->permissionsGroup->view_status) {
                $Topics = Topic::where('created_by', '=', Auth::user()->id)->where('webmaster_id', '=',
                    $webmasterId)->orderby('row_no',
                    'asc')->paginate(env('BACKEND_PAGINATION'));
            } else {
                $Topics = Topic::where('webmaster_id', '=', $webmasterId)->orderby('row_no',
                    'asc')->paginate(env('BACKEND_PAGINATION'));
            }
            return view("backEnd.topics", compact("Topics", "GeneralWebmasterSections", "WebmasterSection"));
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $webmasterId
     * @return \Illuminate\Http\Response
     */
    public function create($webmasterId)
    {
        // Check Permissions
        if (!@Auth::user()->permissionsGroup->add_status) {
            return Redirect::to(route('NoPermission'))->send();
        }
        //
        // General for all pages
        $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
        // General END

        //Webmaster Topic Details
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            $fatherSections = Section::where('webmaster_id', '=', $webmasterId)->where('father_id', '=',
                '0')->orderby('row_no', 'asc')->get();

            return view("backEnd.topics.create",
                compact("GeneralWebmasterSections", "WebmasterSection", "fatherSections"));
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $webmasterId)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $this->validate($request, [
                'photo_file' => 'mimes:png,jpeg,jpg,gif',
                'audio_file' => 'mimes:mpga,wav', // mpga = mp3
                'video_file' => 'mimes:mp4,ogv,webm'
            ]);


            $next_nor_no = Topic::where('webmaster_id', '=', $webmasterId)->max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }

            // Start of Upload Files
            $formFileName = "photo_file";
            $fileFinalName = "";
            if ($request->$formFileName != "") {
                $fileFinalName = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $path = $this->uploadPath;
                $request->file($formFileName)->move($path, $fileFinalName);
            }

            $formFileName = "audio_file";
            $audioFileFinalName = "";
            if ($request->$formFileName != "") {
                $audioFileFinalName = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $path = $this->uploadPath;
                $request->file($formFileName)->move($path, $audioFileFinalName);
            }

            $formFileName = "attach_file";
            $attachFileFinalName = "";
            if ($request->$formFileName != "") {
                $attachFileFinalName = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $path = $this->uploadPath;
                $request->file($formFileName)->move($path, $attachFileFinalName);
            }

            if ($request->video_type == 3) {
                $videoFileFinalName = $request->embed_link;
            } elseif ($request->video_type == 2) {
                $videoFileFinalName = $request->vimeo_link;
            } elseif ($request->video_type == 1) {
                $videoFileFinalName = $request->youtube_link;
            } else {
                $formFileName = "video_file";
                $videoFileFinalName = "";
                if ($request->$formFileName != "") {
                    $videoFileFinalName = time() . rand(1111,
                            9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                    $path = $this->uploadPath;
                    $request->file($formFileName)->move($path, $videoFileFinalName);
                }

            }
            // End of Upload Files


            // create new topic
            $Topic = new Topic;

            // Save topic details
            $Topic->row_no = $next_nor_no;
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $Topic->{"title_" . $ActiveLanguage->code} = $request->{"title_" . $ActiveLanguage->code};
                    $Topic->{"details_" . $ActiveLanguage->code} = $request->{"details_" . $ActiveLanguage->code};

                    // meta info
                    $Topic->{"seo_title_" . $ActiveLanguage->code} = $request->{"title_" . $ActiveLanguage->code};
                    $Topic->{"seo_description_" . $ActiveLanguage->code} = mb_substr(strip_tags(stripslashes($request->{"details_" . $ActiveLanguage->code})), 0, 165, 'UTF-8');
                    $Topic->{"seo_url_slug_" . $ActiveLanguage->code} = Helper::URLSlug($request->{"title_" . $ActiveLanguage->code}, "topic", 0);

                }
            }
            $Topic->date = $request->date;
            if (@$request->expire_date != "") {
                $Topic->expire_date = $request->expire_date;
            }
            if ($fileFinalName != "") {
                $Topic->photo_file = $fileFinalName;
            }
            if ($audioFileFinalName != "") {
                $Topic->audio_file = $audioFileFinalName;
            }
            if ($attachFileFinalName != "") {
                $Topic->attach_file = $attachFileFinalName;
            }
            if ($videoFileFinalName != "") {
                $Topic->video_file = $videoFileFinalName;
            }
            $Topic->icon = $request->icon;
            $Topic->video_type = $request->video_type;
            $Topic->webmaster_id = $webmasterId;
            $Topic->created_by = Auth::user()->id;
            $Topic->visits = 0;
            $Topic->status = 1;

            $Topic->save();

            if ($request->section_id != "" && $request->section_id != 0) {
                // Save categories
                foreach ($request->section_id as $category) {
                    if ($category > 0) {
                        $TopicCategory = new TopicCategory;
                        $TopicCategory->topic_id = $Topic->id;
                        $TopicCategory->section_id = $category;
                        $TopicCategory->save();
                    }
                }
            }

            // Save additional Fields
            if (count($WebmasterSection->customFields) > 0) {
                foreach ($WebmasterSection->customFields as $customField) {
                    $field_value_var = "customField_" . $customField->id;

                    if ($request->$field_value_var != "") {
                        if ($customField->type == 8 || $customField->type == 9 || $customField->type == 10) {
                            // upload file
                            if ($request->$field_value_var != "") {
                                $uploadedFileFinalName = time() . rand(1111,
                                        9999) . '.' . $request->file($field_value_var)->getClientOriginalExtension();
                                $path = $this->uploadPath;
                                $request->file($field_value_var)->move($path, $uploadedFileFinalName);
                                $field_value = $uploadedFileFinalName;
                            }
                        } elseif ($customField->type == 7) {
                            // if multi check
                            $field_value = implode(", ", $request->$field_value_var);
                        } else {
                            $field_value = $request->$field_value_var;
                        }
                        $TopicField = new TopicField;
                        $TopicField->topic_id = $Topic->id;
                        $TopicField->field_id = $customField->id;
                        $TopicField->field_value = $field_value;
                        $TopicField->save();
                    }

                }
            }


            return redirect()->action('TopicsController@edit', [$webmasterId, $Topic->id])->with('doneMessage',
                __('backend.addDone'));
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public function edit($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->edit_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            //
            // General for all pages
            $GeneralWebmasterSections = WebmasterSection::where('status', '=', '1')->orderby('row_no', 'asc')->get();
            // General END

            if (@Auth::user()->permissionsGroup->view_status) {
                $Topics = Topic::where('created_by', '=', Auth::user()->id)->find($id);
            } else {
                $Topics = Topic::find($id);
            }
            if (!empty($Topics)) {
                //Topic Topics Details
                $WebmasterSection = WebmasterSection::find($Topics->webmaster_id);

                $fatherSections = Section::where('webmaster_id', '=', $webmasterId)->where('father_id', '=',
                    '0')->orderby('row_no', 'asc')->get();

                return view("backEnd.topics.edit",
                    compact("Topics", "GeneralWebmasterSections", "WebmasterSection", "fatherSections"));
            } else {
                return redirect()->action('TopicsController@index', $webmasterId);
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $Topic = Topic::find($id);
            if (!empty($Topic)) {


                $this->validate($request, [
                    'photo_file' => 'mimes:png,jpeg,jpg,gif',
                    'audio_file' => 'mimes:mpga,wav', // mpga = mp3
                    'video_file' => 'mimes:mp4,ogv,webm'
                ]);


                // Start of Upload Files
                $formFileName = "photo_file";
                $fileFinalName = "";
                if ($request->$formFileName != "") {
                    // Delete a Topic photo
                    if ($Topic->$formFileName != "") {
                        File::delete($this->uploadPath . $Topic->$formFileName);
                    }

                    $fileFinalName = time() . rand(1111,
                            9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                    $path = $this->uploadPath;
                    $request->file($formFileName)->move($path, $fileFinalName);
                }


                $formFileName = "audio_file";
                $audioFileFinalName = "";
                if ($request->$formFileName != "") {
                    // Delete file if there is a new one
                    if ($Topic->$formFileName != "") {
                        File::delete($this->uploadPath . $Topic->$formFileName);
                    }

                    $audioFileFinalName = time() . rand(1111,
                            9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                    $path = $this->uploadPath;
                    $request->file($formFileName)->move($path, $audioFileFinalName);
                }

                $formFileName = "attach_file";
                $attachFileFinalName = "";
                if ($request->$formFileName != "") {
                    // Delete file if there is a new one
                    if ($Topic->$formFileName != "") {
                        File::delete($this->uploadPath . $Topic->$formFileName);
                    }
                    $attachFileFinalName = time() . rand(1111,
                            9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                    $path = $this->uploadPath;
                    $request->file($formFileName)->move($path, $attachFileFinalName);
                }

                if ($request->video_type == 3) {
                    $videoFileFinalName = $request->embed_link;
                } elseif ($request->video_type == 2) {
                    $videoFileFinalName = $request->vimeo_link;
                } elseif ($request->video_type == 1) {
                    $videoFileFinalName = $request->youtube_link;
                } else {
                    $formFileName = "video_file";
                    $videoFileFinalName = "";
                    if ($request->$formFileName != "") {
                        // Delete file if there is a new one
                        if ($Topic->$formFileName != "") {
                            File::delete($this->uploadPath . $Topic->$formFileName);
                        }
                        $videoFileFinalName = time() . rand(1111,
                                9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                        $path = $this->uploadPath;
                        $request->file($formFileName)->move($path, $videoFileFinalName);
                    }

                }
                // End of Upload Files
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $Topic->{"title_" . $ActiveLanguage->code} = $request->{"title_" . $ActiveLanguage->code};
                        $Topic->{"details_" . $ActiveLanguage->code} = $request->{"details_" . $ActiveLanguage->code};
                    }
                }
                $Topic->date = $request->date;
                if (@$request->expire_date != "" || $Topic->date != "") {
                    $Topic->expire_date = @$request->expire_date;
                }

                if ($request->photo_delete == 1) {
                    // Delete photo_file
                    if ($Topic->photo_file != "") {
                        File::delete($this->uploadPath . $Topic->photo_file);
                    }

                    $Topic->photo_file = "";
                }

                if ($fileFinalName != "") {
                    $Topic->photo_file = $fileFinalName;
                }
                if ($audioFileFinalName != "") {
                    $Topic->audio_file = $audioFileFinalName;
                }

                if ($request->attach_delete == 1) {
                    // Delete attach_file
                    if ($Topic->attach_file != "") {
                        File::delete($this->uploadPath . $Topic->attach_file);
                    }

                    $Topic->attach_file = "";
                }

                if ($attachFileFinalName != "") {
                    $Topic->attach_file = $attachFileFinalName;
                }
                if ($videoFileFinalName != "") {
                    $Topic->video_file = $videoFileFinalName;
                }

                $Topic->icon = $request->icon;
                $Topic->video_type = $request->video_type;
                $Topic->status = $request->status;
                $Topic->updated_by = Auth::user()->id;
                $Topic->save();

                // Remove old categories
                TopicCategory::where('topic_id', $Topic->id)->delete();
                // Save new categories
                if ($request->section_id != "" && $request->section_id != 0) {
                    foreach ($request->section_id as $category) {
                        if ($category > 0) {
                            $TopicCategory = new TopicCategory;
                            $TopicCategory->topic_id = $Topic->id;
                            $TopicCategory->section_id = $category;
                            $TopicCategory->save();
                        }
                    }
                }

                // Remove old Fields Values
                TopicField::where('topic_id', $Topic->id)->delete();
                // Save additional Fields
                if (count($WebmasterSection->customFields) > 0) {
                    foreach ($WebmasterSection->customFields as $customField) {
                        $field_value = "";
                        $field_value_var = "customField_" . $customField->id;
                        $file_del_id = 'file_delete_' . $customField->id;
                        $file_old_id = 'file_old_' . $customField->id;

                        if ($customField->type == 8 || $customField->type == 9 || $customField->type == 10) {
                            // upload file
                            if ($request->$field_value_var != "") {
                                $uploadedFileFinalName = time() . rand(1111,
                                        9999) . '.' . $request->file($field_value_var)->getClientOriginalExtension();
                                $path = $this->uploadPath;
                                $request->file($field_value_var)->move($path, $uploadedFileFinalName);
                                $field_value = $uploadedFileFinalName;
                            } else {
                                // if old file still
                                $field_value = $request->$file_old_id;
                            }
                            if ($request->$file_del_id) {
                                // if want to delete the file
                                File::delete($this->uploadPath . $request->$file_old_id);
                                $field_value = "";
                            }
                        } elseif ($customField->type == 7) {
                            // if multi check
                            if ($request->$field_value_var != "") {
                                $field_value = implode(", ", $request->$field_value_var);
                            }
                        } else {
                            $field_value = $request->$field_value_var;
                        }
                        if ($field_value != "") {
                            $TopicField = new TopicField;
                            $TopicField->topic_id = $Topic->id;
                            $TopicField->field_id = $customField->id;
                            $TopicField->field_value = $field_value;
                            $TopicField->save();
                        }
                    }
                }

                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.saveDone'));
            } else {
                return redirect()->action('TopicsController@index', $webmasterId);
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public function destroy($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            //
            if (@Auth::user()->permissionsGroup->view_status) {
                $Topic = Topic::where('created_by', '=', Auth::user()->id)->find($id);
            } else {
                $Topic = Topic::find($id);
            }
            if (!empty($Topic)) {
                // Delete a Topic photo
                if ($Topic->photo_file != "") {
                    File::delete($this->uploadPath . $Topic->photo_file);
                }
                if ($Topic->attach_file != "") {
                    File::delete($this->uploadPath . $Topic->attach_file);
                }
                if ($Topic->audio_file != "") {
                    File::delete($this->uploadPath . $Topic->audio_file);
                }
                if ($Topic->video_type == 0 && $Topic->video_file != "") {
                    File::delete($this->uploadPath . $Topic->video_file);
                }
                //delete additional fields
                TopicField::where('topic_id', $Topic->id)->delete();
                //delete Related Topics
                RelatedTopic::where('topic_id', $Topic->id)->delete();
                // Remove categories
                TopicCategory::where('topic_id', $Topic->id)->delete();
                // Remove comments
                Comment::where('topic_id', $Topic->id)->delete();
                // Remove maps
                Map::where('topic_id', $Topic->id)->delete();
                // Remove Photos
                $PhotoFiles = Photo::where('topic_id', $Topic->id)->get();
                if (count($PhotoFiles) > 0) {
                    foreach ($PhotoFiles as $PhotoFile) {
                        if ($PhotoFile->file != "") {
                            File::delete($this->uploadPath . $PhotoFile->file);
                        }
                    }
                }
                Photo::where('topic_id', $Topic->id)->delete();
                // Remove Attach Files
                $AttachFiles = AttachFile::where('topic_id', $Topic->id)->get();
                if (count($AttachFiles) > 0) {
                    foreach ($AttachFiles as $AttachFile) {
                        if ($AttachFile->file != "") {
                            File::delete($this->uploadPath . $AttachFile->file);
                        }
                    }
                }
                AttachFile::where('topic_id', $Topic->id)->delete();

                //Remove Topic
                $Topic->delete();
                return redirect()->action('TopicsController@index', $webmasterId)->with('doneMessage',
                    __('backend.deleteDone'));
            } else {
                return redirect()->action('TopicsController@index', $webmasterId);
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Update all selected resources in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param buttonNames , array $ids[],$webmasterId
     * @return \Illuminate\Http\Response
     */
    public function updateAll(Request $request, $webmasterId)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            if ($request->action == "order") {
                foreach ($request->row_ids as $rowId) {
                    $Topic = Topic::find($rowId);
                    if (!empty($Topic)) {
                        $row_no_val = "row_no_" . $rowId;
                        $Topic->row_no = $request->$row_no_val;
                        $Topic->save();
                    }
                }

            } else {
                if ($request->ids != "") {
                    if ($request->action == "activate") {
                        Topic::wherein('id', $request->ids)
                            ->update(['status' => 1]);

                    } elseif ($request->action == "block") {
                        Topic::wherein('id', $request->ids)
                            ->update(['status' => 0]);

                    } elseif ($request->action == "delete") {
                        // Check Permissions
                        if (!@Auth::user()->permissionsGroup->delete_status) {
                            return Redirect::to(route('NoPermission'))->send();
                        }
                        // Delete Topics photo
                        $Topics = Topic::wherein('id', $request->ids)->get();
                        foreach ($Topics as $Topic) {
                            if ($Topic->photo_file != "") {
                                File::delete($this->uploadPath . $Topic->photo_file);
                            }
                            if ($Topic->attach_file != "") {
                                File::delete($this->uploadPath . $Topic->attach_file);
                            }
                            if ($Topic->audio_file != "") {
                                File::delete($this->uploadPath . $Topic->audio_file);
                            }
                            if ($Topic->video_type == 0 && $Topic->video_file != "") {
                                File::delete($this->uploadPath . $Topic->video_file);
                            }
                        }

                        // Delete photo files
                        $PhotoFiles = Photo::wherein('topic_id', $request->ids)->get();
                        foreach ($PhotoFiles as $PhotoFile) {
                            if ($PhotoFile->file != "") {
                                File::delete($this->uploadPath . $PhotoFile->file);
                            }
                        }

                        // Delete attach files
                        $AttachFile_Files = AttachFile::wherein('topic_id', $request->ids)->get();
                        foreach ($AttachFile_Files as $AttachFile_File) {
                            if ($AttachFile_File->file != "") {
                                File::delete($this->uploadPath . $AttachFile_File->file);
                            }
                        }

                        //delete additional fields
                        TopicField::wherein('topic_id', $request->ids)
                            ->delete();
                        //delete Related Topics
                        RelatedTopic::wherein('topic_id', $request->ids)
                            ->delete();
                        // Remove categories
                        TopicCategory::wherein('topic_id', $request->ids)
                            ->delete();
                        // Remove Photos
                        Photo::wherein('topic_id', $request->ids)
                            ->delete();
                        // Remove Attach Files
                        AttachFile::wherein('topic_id', $request->ids)
                            ->delete();
                        // Remove Attach Maps
                        Map::wherein('topic_id', $request->ids)
                            ->delete();
                        // Remove Attach Comments
                        Comment::wherein('topic_id', $request->ids)
                            ->delete();

                        //Remove Topics
                        Topic::wherein('id', $request->ids)
                            ->delete();

                    }
                }
            }
            return redirect()->action('TopicsController@index', $webmasterId)->with('doneMessage',
                __('backend.saveDone'));
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Update SEO tab
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */

    public
    function seo(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $Topic = Topic::find($id);
            if (!empty($Topic)) {
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if($ActiveLanguage->box_status) {
                        $Topic->{"seo_title_" . $ActiveLanguage->code} = $request->{"seo_title_" . $ActiveLanguage->code};
                        $Topic->{"seo_description_" . $ActiveLanguage->code} = $request->{"seo_description_" . $ActiveLanguage->code};
                        $Topic->{"seo_keywords_" . $ActiveLanguage->code} = $request->{"seo_keywords_" . $ActiveLanguage->code};
                        $Topic->{"seo_url_slug_" . $ActiveLanguage->code} = Helper::URLSlug($request->{"seo_url_slug_" . $ActiveLanguage->code}, "topic", $id);
                    }
                }
                $Topic->updated_by = Auth::user()->id;
                $Topic->save();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.saveDone'))->with('activeTab', 'seo');
            } else {
                return redirect()->action('TopicsController@index', $webmasterId);
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Store a newly photos.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public
    function photos(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $this->validate($request, [
                'file' => 'image',
            ]);

            $next_nor_no = Photo::where('topic_id', '=', $id)->max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }

            // Start of Upload Files
            $formFileName = "file";
            $fileFinalName = "";
            $fileFinalTitle = ""; // Original file name without extension
            if ($request->$formFileName != "") {
                $fileFinalTitle = basename($request->file($formFileName)->getClientOriginalName(),
                    '.' . $request->file($formFileName)->getClientOriginalExtension());
                $fileFinalName = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $path = $this->uploadPath;
                $request->file($formFileName)->move($path, $fileFinalName);
            }
            // End of Upload Files
            if ($fileFinalName != "") {
                $Photo = new Photo;
                $Photo->row_no = $next_nor_no;
                $Photo->file = $fileFinalName;
                $Photo->title = $fileFinalTitle;
                $Photo->topic_id = $id;
                $Photo->created_by = Auth::user()->id;
                $Photo->save();

                return response()->json('success', 200);
            } else {
                return response()->json('error', 400);
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $photo_id
     * @return \Illuminate\Http\Response
     */
    public
    function photosDestroy($webmasterId, $id, $photo_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            //
            $Photo = Photo::find($photo_id);
            if (!empty($Photo)) {
                // Delete a Topic photo
                if ($Photo->file != "") {
                    File::delete($this->uploadPath . $Photo->file);
                }


                $Photo->delete();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.deleteDone'))->with('activeTab', 'photos');
            } else {
                return redirect()->action('TopicsController@index', $webmasterId);
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Update all selected resources in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param buttonNames , array $ids[],$webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function photosUpdateAll(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            if ($request->action == "order") {
                foreach ($request->row_ids as $rowId) {
                    $Photo = Photo::find($rowId);
                    if (!empty($Photo)) {
                        $row_no_val = "row_no_" . $rowId;
                        $Photo->row_no = $request->$row_no_val;
                        $Photo->save();
                    }
                }

            } else {
                if ($request->ids != "") {
                    if ($request->action == "delete") {
                        // Check Permissions
                        if (!@Auth::user()->permissionsGroup->delete_status) {
                            return Redirect::to(route('NoPermission'))->send();
                        }
                        // Delete Photos
                        $Photos = Photo::wherein('id', $request->ids)->get();
                        foreach ($Photos as $Photo) {
                            if ($Photo->file != "") {
                                File::delete($this->uploadPath . $Photo->file);
                            }
                        }

                        Photo::wherein('id', $request->ids)
                            ->delete();

                    }
                }
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'photos');
        } else {
            return redirect()->route('NotFound');
        }
    }


// Comments Functions

    /**
     * Show all comments.
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function topicsComments($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'comments');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function commentsCreate($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->add_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab',
                'comments')->with('commentST', 'create');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public
    function commentsStore(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'comment' => 'required'
            ]);


            $next_nor_no = Comment::where('topic_id', '=', $id)->max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }

            $Comment = new Comment;
            $Comment->row_no = $next_nor_no;
            $Comment->name = $request->name;
            $Comment->email = $request->email;
            $Comment->comment = $request->comment;
            $Comment->topic_id = $id;
            $Comment->date = date("Y-m-d H:i:s");
            $Comment->status = 1;
            $Comment->created_by = Auth::user()->id;
            $Comment->save();

            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'comments');
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $comment_id
     * @return \Illuminate\Http\Response
     */
    public
    function commentsEdit($webmasterId, $id, $comment_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->edit_status) {
                return Redirect::to(route('NoPermission'))->send();
            }

            $Comment = Comment::find($comment_id);
            if (!empty($Comment)) {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab',
                    'comments')->with('commentST', 'edit')->with('Comment', $Comment);
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'comments');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param int $webmasterId
     * @param int $comment_id
     * @return \Illuminate\Http\Response
     */
    public
    function commentsUpdate(Request $request, $webmasterId, $id, $comment_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $Comment = Comment::find($comment_id);
            if (!empty($Comment)) {


                $this->validate($request, [
                    'name' => 'required',
                    'email' => 'required',
                    'comment' => 'required'
                ]);
                $Comment->name = $request->name;
                $Comment->email = $request->email;
                $Comment->comment = $request->comment;
                $Comment->status = $request->status;
                $Comment->updated_by = Auth::user()->id;
                $Comment->save();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.saveDone'))->with('activeTab', 'comments');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'comments');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $comment_id
     * @return \Illuminate\Http\Response
     */
    public
    function commentsDestroy($webmasterId, $id, $comment_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            //
            $Comment = Comment::find($comment_id);
            if (!empty($Comment)) {
                $Comment->delete();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.deleteDone'))->with('activeTab', 'comments');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'comments');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Update all selected resources in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param buttonNames , array $ids[],$webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function commentsUpdateAll(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            if ($request->action == "order") {
                foreach ($request->row_ids as $rowId) {
                    $Comment = Comment::find($rowId);
                    if (!empty($Comment)) {
                        $row_no_val = "row_no_" . $rowId;
                        $Comment->row_no = $request->$row_no_val;
                        $Comment->save();
                    }
                }
            } else {
                if ($request->ids != "") {
                    if ($request->action == "activate") {
                        Comment::wherein('id', $request->ids)
                            ->update(['status' => 1]);

                    } elseif ($request->action == "block") {
                        Comment::wherein('id', $request->ids)
                            ->update(['status' => 0]);

                    } elseif ($request->action == "delete") {
                        // Check Permissions
                        if (!@Auth::user()->permissionsGroup->delete_status) {
                            return Redirect::to(route('NoPermission'))->send();
                        }

                        Comment::wherein('id', $request->ids)
                            ->delete();

                    }
                }
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'comments');
        } else {
            return redirect()->route('NotFound');
        }
    }


// Maps Functions

    /**
     * Show all Maps.
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function topicsMaps($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'maps');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function mapsCreate($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->add_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab',
                'maps')->with('mapST', 'create');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public
    function mapsStore(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $this->validate($request, [
                'longitude' => 'required',
                'longitude' => 'required'
            ]);


            $next_nor_no = Map::where('topic_id', '=', $id)->max('row_no');
            if ($next_nor_no < 1) {
                $next_nor_no = 1;
            } else {
                $next_nor_no++;
            }

            $Map = new Map;
            $Map->row_no = $next_nor_no;
            $Map->longitude = $request->longitude;
            $Map->latitude = $request->latitude;
            foreach (Helper::languagesList() as $ActiveLanguage) {
                if ($ActiveLanguage->box_status) {
                    $Map->{"title_" . $ActiveLanguage->code} = $request->{"title_" . $ActiveLanguage->code};
                    $Map->{"details_" . $ActiveLanguage->code} = $request->{"details_" . $ActiveLanguage->code};
                }
            }
            $Map->icon = $request->icon;
            $Map->topic_id = $id;
            $Map->status = 1;
            $Map->created_by = Auth::user()->id;
            $Map->save();

            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'maps');
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $map_id
     * @return \Illuminate\Http\Response
     */
    public
    function mapsEdit($webmasterId, $id, $map_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->edit_status) {
                return Redirect::to(route('NoPermission'))->send();
            }

            $Map = Map::find($map_id);
            if (!empty($Map)) {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab',
                    'maps')->with('mapST', 'edit')->with('Map', $Map);
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'maps');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param int $webmasterId
     * @param int $map_id
     * @return \Illuminate\Http\Response
     */
    public
    function mapsUpdate(Request $request, $webmasterId, $id, $map_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $Map = Map::find($map_id);
            if (!empty($Map)) {


                $this->validate($request, [
                    'longitude' => 'required',
                    'longitude' => 'required'
                ]);
                $Map->longitude = $request->longitude;
                $Map->latitude = $request->latitude;
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $Map->{"title_" . $ActiveLanguage->code} = $request->{"title_" . $ActiveLanguage->code};
                        $Map->{"details_" . $ActiveLanguage->code} = $request->{"details_" . $ActiveLanguage->code};
                    }
                }
                $Map->icon = $request->icon;
                $Map->status = $request->status;
                $Map->updated_by = Auth::user()->id;
                $Map->save();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.saveDone'))->with('activeTab', 'maps');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'maps');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $map_id
     * @return \Illuminate\Http\Response
     */
    public
    function mapsDestroy($webmasterId, $id, $map_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            //
            $Map = Map::find($map_id);
            if (!empty($Map)) {
                $Map->delete();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.deleteDone'))->with('activeTab', 'maps');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'maps');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Update all selected resources in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param buttonNames , array $ids[],$webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function mapsUpdateAll(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            if ($request->action == "order") {
                foreach ($request->row_ids as $rowId) {
                    $Map = Map::find($rowId);
                    if (!empty($Map)) {
                        $row_no_val = "row_no_" . $rowId;
                        $Map->row_no = $request->$row_no_val;
                        $Map->save();
                    }
                }
            } else {
                if ($request->ids != "") {
                    if ($request->action == "activate") {
                        Map::wherein('id', $request->ids)
                            ->update(['status' => 1]);

                    } elseif ($request->action == "block") {
                        Map::wherein('id', $request->ids)
                            ->update(['status' => 0]);

                    } elseif ($request->action == "delete") {

                        // Check Permissions
                        if (!@Auth::user()->permissionsGroup->delete_status) {
                            return Redirect::to(route('NoPermission'))->send();
                        }

                        Map::wherein('id', $request->ids)
                            ->delete();

                    }
                }
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'maps');
        } else {
            return redirect()->route('NotFound');
        }
    }


// Files Functions

    /**
     * Show all files.
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function topicsFiles($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'files');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function filesCreate($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->add_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab',
                'files')->with('fileST', 'create');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public
    function filesStore(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            $this->validate($request, [
                'file' => 'required'
            ]);

            // Start of Upload Files
            $formFileName = "file";
            $fileFinalName = "";
            if ($request->$formFileName != "") {
                $fileFinalName = time() . rand(1111,
                        9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                $path = $this->uploadPath;
                $request->file($formFileName)->move($path, $fileFinalName);
            }
            if ($fileFinalName != "") {

                $next_nor_no = AttachFile::where('topic_id', '=', $id)->max('row_no');
                if ($next_nor_no < 1) {
                    $next_nor_no = 1;
                } else {
                    $next_nor_no++;
                }

                $AttachFile = new AttachFile;
                $AttachFile->topic_id = $id;
                $AttachFile->row_no = $next_nor_no;
                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $AttachFile->{"title_" . $ActiveLanguage->code} = $request->{"title_" . $ActiveLanguage->code};
                    }
                }
                $AttachFile->file = $fileFinalName;
                $AttachFile->created_by = Auth::user()->id;
                $AttachFile->save();

                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.saveDone'))->with('activeTab', 'files');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'files');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $file_id
     * @return \Illuminate\Http\Response
     */
    public
    function filesEdit($webmasterId, $id, $file_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->edit_status) {
                return Redirect::to(route('NoPermission'))->send();
            }

            $AttachFile = AttachFile::find($file_id);
            if (!empty($AttachFile)) {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab',
                    'files')->with('fileST', 'edit')->with('AttachFile', $AttachFile);
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'files');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param int $webmasterId
     * @param int $file_id
     * @return \Illuminate\Http\Response
     */
    public
    function filesUpdate(Request $request, $webmasterId, $id, $file_id)
    {

        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //

            $AttachFile = AttachFile::find($file_id);
            if (!empty($AttachFile)) {

                // Start of Upload Files
                $formFileName = "file";
                $fileFinalName = "";
                if ($request->$formFileName != "") {
                    // Delete a Topic photo
                    if ($AttachFile->$formFileName != "") {
                        File::delete($this->uploadPath . $AttachFile->$formFileName);
                    }

                    $fileFinalName = time() . rand(1111,
                            9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
                    $path = $this->uploadPath;
                    $request->file($formFileName)->move($path, $fileFinalName);
                }

                foreach (Helper::languagesList() as $ActiveLanguage) {
                    if ($ActiveLanguage->box_status) {
                        $AttachFile->{"title_" . $ActiveLanguage->code} = $request->{"title_" . $ActiveLanguage->code};
                    }
                }
                if ($fileFinalName != "") {
                    $AttachFile->file = $fileFinalName;
                }
                $AttachFile->updated_by = Auth::user()->id;
                $AttachFile->save();

                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.saveDone'))->with('activeTab', 'files');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'files');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $file_id
     * @return \Illuminate\Http\Response
     */
    public
    function filesDestroy($webmasterId, $id, $file_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            //
            $AttachFile = AttachFile::find($file_id);
            if (!empty($AttachFile)) {
                // Delete file
                if ($AttachFile->file != "") {
                    File::delete($this->uploadPath . $AttachFile->file);
                }

                $AttachFile->delete();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.deleteDone'))->with('activeTab', 'files');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'files');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Update all selected resources in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param buttonNames , array $ids[],$webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function filesUpdateAll(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            if ($request->action == "order") {
                foreach ($request->row_ids as $rowId) {
                    $AttachFile = AttachFile::find($rowId);
                    if (!empty($AttachFile)) {
                        $row_no_val = "row_no_" . $rowId;
                        $AttachFile->row_no = $request->$row_no_val;
                        $AttachFile->save();
                    }
                }
            } else {
                if ($request->ids != "") {
                    if ($request->action == "delete") {
                        // Check Permissions
                        if (!@Auth::user()->permissionsGroup->delete_status) {
                            return Redirect::to(route('NoPermission'))->send();
                        }

                        // Delete Topics photo
                        $AttachFiles = AttachFile::wherein('id', $request->ids)->get();
                        foreach ($AttachFiles as $AttachFile) {
                            if ($AttachFile->file != "") {
                                File::delete($this->uploadPath . $AttachFile->file);
                            }
                        }

                        AttachFile::wherein('id', $request->ids)
                            ->delete();

                    }
                }
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'files');
        } else {
            return redirect()->route('NotFound');
        }
    }


// Related Topics Functions

    /**
     * Show all Related Topics .
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function topicsRelated($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'related');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Show all Related Topics .
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function topicsRelatedLoad($id)
    {

        $link_title_var = "title_" . @Helper::currentLanguage()->code;
        $TopicsLoaded = Topic::where('webmaster_id', '=', $id)->orderby('row_no', 'asc')->get();
        $i = 0;
        foreach ($TopicsLoaded as $TopicLoaded) {
            $title = $TopicLoaded->$link_title_var;
            $tid = $TopicLoaded->id;
            echo "
<label class=\"ui-check\">
<input type='checkbox' name='related_topics_id[]' value='$tid' id='related_topics_$i' class=''>
<i class=\"dark-white\"></i> &nbsp;<label for=\"related_topics_$i\">$title</label>
</label>
        ";
            echo "<br>";
            $i++;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function relatedCreate($webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->add_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab',
                'related')->with('relatedST', 'create');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $webmasterId
     * @return \Illuminate\Http\Response
     */
    public
    function relatedStore(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            foreach ($request->related_topics_id as $related_topic_id) {
                $next_nor_no = RelatedTopic::where('topic_id', '=', $id)->max('row_no');
                if ($next_nor_no < 1) {
                    $next_nor_no = 1;
                } else {
                    $next_nor_no++;
                }

                $RelatedTopic = new RelatedTopic;
                $RelatedTopic->topic_id = $id;
                $RelatedTopic->topic2_id = $related_topic_id;
                $RelatedTopic->row_no = $next_nor_no;
                $RelatedTopic->created_by = Auth::user()->id;
                $RelatedTopic->save();
            }
            if (count($request->related_topics_id) > 0) {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.saveDone'))->with('activeTab', 'related');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param int $webmasterId
     * @param int $file_id
     * @return \Illuminate\Http\Response
     */
    public
    function relatedDestroy($webmasterId, $id, $file_id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            // Check Permissions
            if (!@Auth::user()->permissionsGroup->delete_status) {
                return Redirect::to(route('NoPermission'))->send();
            }
            //
            $RelatedTopic = RelatedTopic::find($file_id);
            if (!empty($RelatedTopic)) {
                $RelatedTopic->delete();
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                    __('backend.deleteDone'))->with('activeTab', 'related');
            } else {
                return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('activeTab', 'related');
            }
        } else {
            return redirect()->route('NotFound');
        }
    }


    /**
     * Update all selected resources in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param buttonNames , array $ids[],$webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function relatedUpdateAll(Request $request, $webmasterId, $id)
    {
        $WebmasterSection = WebmasterSection::find($webmasterId);
        if (!empty($WebmasterSection)) {
            //
            if ($request->action == "order") {
                foreach ($request->row_ids as $rowId) {
                    $RelatedTopic = RelatedTopic::find($rowId);
                    if (!empty($RelatedTopic)) {
                        $row_no_val = "row_no_" . $rowId;
                        $RelatedTopic->row_no = $request->$row_no_val;
                        $RelatedTopic->save();
                    }
                }
            } else {
                if ($request->ids != "") {
                    if ($request->action == "delete") {
                        // Check Permissions
                        if (!@Auth::user()->permissionsGroup->delete_status) {
                            return Redirect::to(route('NoPermission'))->send();
                        }

                        RelatedTopic::wherein('id', $request->ids)
                            ->delete();

                    }
                }
            }
            return redirect()->action('TopicsController@edit', [$webmasterId, $id])->with('doneMessage',
                __('backend.saveDone'))->with('activeTab', 'related');
        } else {
            return redirect()->route('NotFound');
        }
    }

    /**
     * Store a newly photos.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $webmasterId
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public
    function upload(Request $request)
    {
        //
        $this->validate($request, [
            'file' => 'image',
        ]);

        // Start of Upload Files
        $formFileName = "file";
        $fileFinalName = "";
        $fileFinalTitle = ""; // Original file name without extension
        if ($request->$formFileName != "") {
            $fileFinalTitle = basename($request->file($formFileName)->getClientOriginalName(),
                '.' . $request->file($formFileName)->getClientOriginalExtension());
            $fileFinalName = time() . rand(1111,
                    9999) . '.' . $request->file($formFileName)->getClientOriginalExtension();
            $path = $this->uploadPath;
            $request->file($formFileName)->move($path, $fileFinalName);
        }
        // End of Upload Files
        if ($fileFinalName != "") {
            return $fileFinalName;
        } else {
            return "Error";
        }

    }
}
