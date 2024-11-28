<?php

namespace HichemTabTech\JetstreamRise\Mail;

use HichemTabTech\JetstreamRise\TeamInvitation as TeamInvitationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The team invitation instance.
     *
     * @var \HichemTabTech\JetstreamRise\TeamInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TeamInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.team-invitation', ['acceptUrl' => URL::signedRoute('team-invitations.accept', [
            'invitation' => $this->invitation,
        ])])->subject(__('Team Invitation'));
    }
}
