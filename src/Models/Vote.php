<?php

namespace Keggermont\Voteable\Models;

use Illuminate\Database\Eloquent\Model;


class Vote extends Model {

    protected $fillable = ['rate', 'author','voteable'];
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = "";

    public function __construct(array $attributes = []) {
        $this->table = config("laravel-voteable.table_name");
        parent::__construct($attributes);
    }

    /**
     * Return the model from configuration
     * @return string
     */
    public function voteableModel() {
        return config('laravel-voteable.model');
    }


    /**
     * @return mixed
     */
    public function voteable() {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function author() {
        return $this->morphTo('author');
    }

    /**
     * @param Model $voteable
     * @param integer $rate
     * @param Model $author
     *
     * @return static
     */
    public function createOrUpdateVote(Model $voteable, $rate, Model $author) {

        $vote = $this::where("author_id", $author->getAuthIdentifier())->where("author_type", get_class($author))->where("voteable_type", get_class($voteable))->where("voteable_id",$voteable->id)->first();
        if($vote) {
            $vote->rate = $rate;
            $vote->save();
        } else {
            $vote = new Vote(["rate" => $rate]);
            $vote->author_id = $author->id;
            $vote->author_type = get_class($author);
            $voteable->votes()->save($vote);
        }
        return $vote;
    }

    /**
     * @param $id
     * @return boolean
     */
    public function deleteVote($id) {

        $obj = static::find($id);
        if($obj->author->id != \Auth::user()->id && (isset(\Auth::user()->is_admin) && !\Auth::user()->is_admin)) {
            // The current logged user was not the author, and it's not an admin !
            return false;
        }
        return (bool)$obj->delete();
    }
}
