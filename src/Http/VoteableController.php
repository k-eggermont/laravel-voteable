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

        $votes = (config('laravel-voteable.model'))::where("voteable_type",$className)->where("voteable_id",$id)->orderBy("updated_at",$order)->with(["childrens","author"])->paginate(config('laravel-voteable.paginate'));

        return array("success" => true, "votes" => $votes);

    }


    /*
     * Création d'un vote
     */

    public function post($type,$id, Request $request) {
        if(!\Auth::check()) {
            return response()->setStatusCode(401, 'You are not connected!');
        }

        $this->validate($request, ["rate" => "integer|min:0|max:100|required"]);

        if(!isset(config('laravel-voteable.allowType')[$type])) {
            throw new \Exception($type." was not in the allowType configuration (laravel-voteable.php)");
        }
        $className = config('laravel-voteable.allowType')[$type];

        // Verification de l'existance de l'objet concerné
        $obj = $className::where("id",$id)->firstOrFail();

        $vote = $obj->createOrUpdateVote(request("rate"), \Auth::user());

        if($vote) {
            return array("success" => true, "vote" => $vote);
        } else {
            throw new \Exception("An error occured");
        }

    }

    /*
     * Suppression d'un vote
     */
    public function delete($id) {
        if(!\Auth::check()) {
            return response()->setStatusCode(401, 'You are not connected!');
        }

        // Verification de l'existance de l'objet concerné
        $vote = (config('laravel-voteable.model'))::where("id",$id)->with("author")->firstOrFail();

        if(\Auth::check() && (($vote->author->id == \Auth::user()->id) OR (isset(\Auth::user()->is_admin) && \Auth::user()->is_admin === true))) {
            $vote->delete();
        } else {
            return response()->setStatusCode(403, 'You are not authorized !');
        }

        return array("success" => true);

    }



}