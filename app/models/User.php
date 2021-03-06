<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {
	use UserTrait, RemindableTrait;

	protected $table = 'users';

	protected $hidden = array('password', 'remember_token');

	public function workouts() {
		return $this->hasMany('Workout');
	}

  public function goals() {
    return $this->hasMany('Goal');
  }

  public function activeGoals() {
    return $this->goals()
                ->with('activity')
                ->whereNull('accomplished_date')
                ->orderBy('target_date', 'asc')
                ->get();
  }

  public function recentlyAccomplishedGoals() {
    $now = Carbon::now();
    $startWeek = $now->subWeek()->toDateString();
    $endWeek = $now->tomorrow()->toDateString();
    return $this->goals()
                ->with('activity')
                ->whereNotNull('accomplished_date')
                ->whereBetween('accomplished_date', array($startWeek, $endWeek))
                ->orderBy('accomplished_date', 'desc')
                ->get();
  }

  public function recentlyLoggedWorkouts() {
    return $this->workouts()
                ->with('activity')
                ->orderBy('created_at', 'desc')
                ->take(25)
                ->get();
  }

	public function getNameAttribute() {
		return $this->first_name . ' ' . $this->last_name;
	}
}
