<?php

namespace App\Http\Controllers;

use App\collabarator;
use App\Http\Traits\AuthTrait;
use App\Notes;
use App\User;
use Illuminate\Http\Request;


class NotesController extends Controller
{
    use AuthTrait;

   public function createNotes(Request $request){
       $note=new Notes();
       $note->title=$request->input('title');
       $note->body=$request->input('body');
       $note->user_id = $this->getData(); 
       $user_mail=User::where('id',$note->user_id)->value('email');
       $note->createdBy=$user_mail;
       $note->save();
       $collabMails=new collabarator();
        $mailAssignedTo= $request->input('collab_mails');
       if($mailAssignedTo != null){
       $collabMails->collab_mails=$mailAssignedTo;
       $collabMails->note_id=$note->id;
       $collabMails->save();
       }
       return $note;
   }


   public function addCollabarator(Request $request){
    $id=$request->input('id');
    $email=$request->input('email');
    $verify=User::where('email',$email)->value('email');
    if(!$verify){
        return response()->json(['Alert'=>"email is not registered"]);
    }
    $Target_note=Notes::findOrFail($id); 
    $check_id=$this->getData();
    if($email==$verify){
        if($Target_note->user_id=$check_id ){
            $assignedValue=Notes::where('id',$id)->update(array('assignedTo'=>$email));
            $collabarator=new collabarator();
            $collabarator->collab_mails=$email;
            $collabarator->note_id=$id;
            $collabarator->save();
            return response()->json(['message'=>"Email has been added successfully"]);
        }
    }
   }

   public function removeMailFromCollabarator(Request $request){
    $id=$request->input('id');
    $email=$request->input('email');
    $Target_note=Notes::findOrFail($id);  
    $check_id=$this->getData();
    if($Target_note->user_id=$check_id ){
        $value_removed=Notes::where('id',$id)->update(array('assignedTo'=>null));
        $collabaratorRemoved=collabarator::where('note_id',$id)->where('collab_mails',$email)->delete();
        return response()->json(['message'=>"Email is removed successfully"]);
    }
   }

   public function getNotes(){
    $notes=Notes::all();       
    $check_id=$this->getData();
    $Normal_notes=User::find($notes->user_id=$check_id )->noteses;
    $email=User::where('id',$check_id)->value('email');
    $assignedMail=$email;  

    if($notes->user_id=$check_id){
        $collabaratorNotes=Notes::select("notes.id","notes.title","notes.body","notes.assignedTo","notes.createdBy")
        ->leftJoin("collabarator","collabarator.collab_mails","=","notes.assignedTo")->distinct()
        ->where("notes.assignedTo","=","$assignedMail")
        ->get();       
    $SharingMails=collabarator::select("collabarator.collab_mails")
        ->leftJoin("notes","notes.id","=","collabarator.note_id")->distinct()->get();          
    return response()->json(['notes'=>$Normal_notes,'collabarator'=>$collabaratorNotes,'sharingEmails'=>$SharingMails]);
     }
    }

}
