<?php
namespace Keggermont\Voteable\Traits;

use Illuminate\Database\Eloquent\Collection;

trait Voteable {

    /**
     * Return the model name
     * @return string $model
     */
    public function voteableModel()
    {
        return config('laravel-voteable.model');
    }

    /**
     * Laravel Polymorphic relation
     * @return Collection $collection
     */
    public function votes()
    {
        return $this->morphMany($this->voteableModel(), 'voteable');
    }

    /**
     * Get SCORE statistics. Return [ "count" => [NUMBER OF VOTES], "avg_100" => [AVERAGE NOTE /100], "avg_10" => [AVERAGE NOTE /10], "avg_5" => [AVERAGE NOTE /5] ]
     * @return array $stats
     */
    public function votesStats() {
        $resume = ["count" => $this->voteCount(), "avg_100" => 0];
        foreach($this->votes as $vote) {
            $resume["avg_100"] += $vote->rate;
        }
        $resume["avg_100"] = round($resume["avg_100"] / $resume["count"]);
        $resume["avg_10"] = round(($resume["avg_100"] / 10) * 2) / 2;
        $resume["avg_5"] = round(($resume["avg_100"] / 20) * 2) / 2;

        return $resume;

    }

    /**
     * Create or Update (if exist) a vote
     * @param $rate
     * @param $author
     * @return mixed
     */
    public function createOrUpdateVote($rate, $author)
    {
        $voteableModel = $this->voteableModel();
        $vote = (new $voteableModel())->createOrUpdateVote($this, $rate, $author);
        return $vote;
    }

    /**
     * @param $id
     *
     * @return boolean
     */
    public function deleteVote($id)
    {
        $voteableModel = $this->voteableModel();
        return (bool) (new $voteableModel())->deleteVote($id);
    }
    /**
     * Return vote count
     * @return integer
     */
    public function voteCount()
    {
        return $this->votes->count();
    }




}