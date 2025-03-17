<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class CommandePreteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;

    /**
     * Create a new message instance.
     *
     * @param Commande $commande
     * @return void
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Générer le PDF
        $pdf = Pdf::loadView('commandes.facture', ['commande' => $this->commande]);

        // Attacher le PDF à l'email
        return $this->subject('Votre commande est prête - ISI BURGER')
            ->view('emails.commande_prete')  // Vue de l'email
            ->attachData($pdf->output(), 'facture_commande_' . $this->commande->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
