<?php

class WorkoutsController extends \BaseController {

	public function index() {
		$workouts = Auth::user()
									->workouts()
									->with('activity')
									->orderBy('created_at', 'desc')
									->paginate(25);

		return View::make('workouts.index')
							->with('workouts', $workouts);
	}

	public function create() {
		$workout = new Workout();
		return View::make('workouts.create')->with('workout', $workout);
	}

	public function store() {
		$data = Input::only('activity_id', 'metric', 'amount', 'duration', 'notes');

		$workoutCreate = new WorkoutCreateService(Auth::user(), $data);

		if(!$workoutCreate->valid()) {
			return Redirect::action('workouts.create')
						->withErrors($workoutCreate->errors())
						->withInput(Input::all());
		}

		$workoutCreate->perform();

		Session::flash('message', 'Successfully logged workout!');
		return Redirect::action('workouts.index');
	}

	public function show($id) {
		$workout = Workout::findOrFail($id);
		$this->authorize('read', $workout);
		return View::make('workouts.show')->with('workout', $workout);
	}

	public function edit($id) {
		$workout = Workout::findOrFail($id);
		$this->authorize('update', $workout);
		return View::make('workouts.edit')->with('workout', $workout);
	}

	public function update($id) {
		$data = Input::only('amount', 'duration', 'notes');

		$workout = Workout::findOrFail($id);

		$this->authorize('update', $workout);

		$workoutUpdate = new WorkoutUpdateService($workout, $data);

		if(!$workoutUpdate->valid()) {
			return Redirect::action('workouts.edit', array($id))
						->withErrors($workoutUpdate->errors())
						->withInput(Input::all());
		}

		$workoutUpdate->perform();

		Session::flash('message', 'Successfully updated workout!');
		return Redirect::action('workouts.index');
	}

	public function destroy($id) {
		$workout = Workout::findOrFail($id);

		$this->authorize('delete', $workout);

		$workoutDestroy = new WorkoutDestroyService($workout);
		$workoutDestroy->perform();

		Session::flash('message', 'Successfully deleted workout.');
		return Redirect::action('workouts.index');
	}

}
