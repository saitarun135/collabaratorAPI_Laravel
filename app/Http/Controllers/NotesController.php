<?php

namespace App\Http\Controllers;

use App\collabarator;
use App\Notes;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotesController extends Controller
{
   public function createNotes(Request $request){
       $note=new Notes();
       $note->title=$request->input('title');
       $note->body=$request->input('body');
       $token = JWTAuth::getToken();
       $id = JWTAuth::getPayload($token)->toArray();
       $note->user_id = $id["sub"]; 
       $user_mail=User::where('id',$note->user_id)->value('email');
       $note->createdBy=$user_mail;
       $note->save();
       $collabMails=new collabarator();
    //    $collabMails->collab_mails=$user_mail;
       $collabMails->note_id=$note->id;
       $collabMails->save();
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
    $token = JWTAuth::getToken();
    $id_getter = JWTAuth::getPayload($token)->toArray();   
    $check_id=$id_getter["sub"];
    if($email==$verify){
        if($Target_note->user_id=$check_id ){
            $assignedValue=Notes::where('id',$id)->update(array('assignedTo'=>$email));
            $collabarator=new collabarator();
            $collabarator->collab_mails=$email;
            $collabarator->note_id=$id;
            $collabarator->save();
            //$collabarator=collabarator::where('id',$id)->update(array('collab_mails'=>$email));
            return response()->json(['message'=>"Email has been added successfully"]);
        }
    }
   }

   public function removeMailFromCollabarator(Request $request){
    $id=$request->input('id');
    $Target_note=Notes::findOrFail($id);
    $token = JWTAuth::getToken();
    $id_getter = JWTAuth::getPayload($token)->toArray();   
    $check_id=$id_getter["sub"];
    if($Target_note->user_id=$check_id ){
        // $note=AppNotes::where('id',$id)->update(array('MailColab'=>null));
        $value_removed=Notes::where('id',$id)->update(array('assignedTo'=>null));
        //$collabaratorRemoved=collabarator::where('note_id',$id)->delete();
        return response()->json(['message'=>"Email is removed successfully"]);
    }
   }
   
   public function getNotes(){
    $notes=Notes::all();
    $token = JWTAuth::getToken();
    $id = JWTAuth::getPayload($token)->toArray();        
    $check_id=$id["sub"];
    $Normal_notes=User::find($notes->user_id=$check_id )->noteses;
    $email=User::where('id',$check_id)->value('email');
    $assignedMail=$email;
    if($notes->user_id=$check_id){
        $collabaratorNotes=Notes::select("notes.id","notes.title","notes.body","notes.assignedTo","notes.createdBy")
        ->leftJoin("collabarator","collabarator.collab_mails","=","notes.assignedTo")
        ->where("notes.assignedTo","=","$assignedMail")
        ->get();
    return response()->json(['notes'=>$Normal_notes,'collabarator'=>$collabaratorNotes]);
    }
   }
}
