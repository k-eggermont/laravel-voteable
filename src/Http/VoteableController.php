<?php
namespace Keggermont\Voteable\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class VoteableController extends Controller {


    /*
     * Récupération des votes
     */

    public function get($type,$id) {

        if(!isset(config('laravel-voteable.allowType')[$type])) {
            throw new \Exception($type." was not in the allowType configuration (laravel-voteable.php)");
        }
        $className = config('laravel-voteable.allowType')[$type];
        $className::where("id",$id)->firstOrFail();

        if(strtolower(config('laravel-voteable.defaultOrderDate')) == "desc" || strtolower(config('laravel-voteable.defaultOrderDate')) == "asc"){
            $order = config('laravel-voteable.defaultOrderDate');
        } else {
            $order = "ASC";
        }

        $comments = (config('laravel-voteable.model'))::where("voteable_type",$className)->where("voteable_id",$id)->orderBy("created_at",$order)->with(["childrens","author"])->paginate(config('laravel-voteable.paginate'));

        return array("success" => true, "comments" => $comments);

    }


    /*
     * Création d'un vote
     */

    public function post($type,$id, Request $request) {
        if(!\Auth::user()) {
            return response()->setStatusCode(401, 'You are not connected!');
        }

        $this->validate($request, ["title" => "string|required", "body" => "string|required"]);

        if(!isset(config('laravel-voteable.allowType')[$type])) {
            throw new \Exception($type." was not in the allowType configuration (laravel-voteable.php)");
        }
        $className = config('laravel-voteable.allowType')[$type];

        // Verification de l'existance de l'objet concerné
        $obj = $className::where("id",$id)->firstOrFail();

        $comment = $obj->createVote(["title" => request("title"),"body" => request("body")], \Auth::user());

        if($comment) {
            return array("success" => true, "comment" => $comment);
        } else {
            throw new \Exception("An error occured");
        }

    }

    /*
     * Suppression d'un vote
     */
    public function delete($id) {
        if(!\Auth::user()) {
            return response()->setStatusCode(401, 'You are not connected!');
        }

        // Verification de l'existance de l'objet concerné
        $comment = (config('laravel-voteable.model'))::where("id",$id)->with("author")->firstOrFail();

        if(($comment->author->id == \Auth::user()->id) OR (isset(\Auth::user()->is_admin) && \Auth::user()->is_admin === true)) {
            $comment->delete();
        } else {
            return response()->setStatusCode(403, 'You are not authorized !');
        }

        return array("success" => true);

    }



}