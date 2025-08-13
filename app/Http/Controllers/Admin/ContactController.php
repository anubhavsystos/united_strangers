<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Mailer;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Exception;

class ContactController extends Controller
{
    protected $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function contact(Request $request)
    {
        try {
            // Mark unread contacts as read
            $this->contact->whereNull('has_read')->update(['has_read' => 1]);

            $query = $this->contact->query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            }

            $contacts = $query->paginate(20);

            return view('admin.contact.index', ['contacts' => $contacts]);
        } catch (Exception $e) {
            Session::flash('error', 'Failed to load contact messages.');
            return redirect()->back();
        }
    }

    public function contact_delete($id)
    {
        try {
            $this->contact->findOrFail($id)->delete();
            Session::flash('success', get_phrase('Contact deleted successfully!'));
        } catch (Exception $e) {
            Session::flash('error', 'Failed to delete contact.');
        }

        return redirect()->back();
    }

    public function contact_reply_form($id)
    {
        try {
            $contact = $this->contact->findOrFail($id);
            return view('admin.contact.reply', compact('contact'));
        } catch (Exception $e) {
            Session::flash('error', 'Contact not found.');
            return redirect()->back();
        }
    }

    public function contact_reply_send(Request $request)
    {
        try {
            $request->validate([
                'send_to' => 'required|exists:contacts,id',
                'subject' => 'required|string|max:255',
                'reply_message' => 'required|string',
            ]);

            $contact = $this->contact->findOrFail($request->send_to);

            $this->send_mail($contact->email, $request->subject, $request->reply_message);

            $contact->update(['replied' => 1]);

            Session::flash('success', get_phrase('Email sent successfully!'));
            return redirect()->route('admin.contact');
        } catch (Exception $e) {
            Session::flash('error', 'Failed to send email reply.');
            return redirect()->back();
        }
    }

    public function send_mail($user_email, $subject, $description)
    {
        try {
            config([
                'mail.mailers.smtp.transport'  => get_settings('smtp_protocol'),
                'mail.mailers.smtp.host'       => get_settings('smtp_host'),
                'mail.mailers.smtp.port'       => get_settings('smtp_port'),
                'mail.mailers.smtp.encryption' => get_settings('smtp_crypto'),
                'mail.mailers.smtp.username'   => get_settings('smtp_username'),
                'mail.mailers.smtp.password'   => get_settings('smtp_password'),
                'mail.from.address'            => get_settings('smtp_username'),
                'mail.from.name'               => get_settings('system_title'),
            ]);

            $mail_data = [
                'subject'     => $subject,
                'description' => $description,
            ];

            Mail::to($user_email)->send(new Mailer($mail_data));
        } catch (Exception $e) {
            // Log or silently fail
        }
    }
}
