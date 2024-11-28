<?php

namespace HichemTabTech\JetstreamRise\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use HichemTabTech\JetstreamRise\Contracts\CreatesTeams;
use HichemTabTech\JetstreamRise\RedirectsActions;
use Livewire\Component;

class CreateTeamForm extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * Create a new team.
     *
     * @param  \HichemTabTech\JetstreamRise\Contracts\CreatesTeams  $creator
     * @return mixed
     */
    public function createTeam(CreatesTeams $creator)
    {
        $this->resetErrorBag();

        $creator->create(Auth::user(), $this->state);

        return $this->redirectPath($creator);
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('teams.create-team-form');
    }
}
