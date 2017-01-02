<?php

namespace App\Http\Controllers;

use App\VbaModels\Domain;
use App\VbaModels\Mailbox;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\MailboxTransformer;


class MailboxController extends ApiController
{
    /**
     * @var string Type to use with Transformer
     */
    protected $type = 'mailboxes';

    /**
     * @param Domain             $domain
     * @param MailboxTransformer $mailboxTransformer
     * @param Manager            $fractal
     */
    public function __construct(Domain $domain, MailboxTransformer $mailboxTransformer, Manager $fractal)
    {
        parent::__construct($domain, $mailboxTransformer, $fractal);
    }
    
    /**
     * All mailboxes for domain.
     * or serach for a single mailbox in that domain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $domainName
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $domainName)
    {
        $domain = $this->getDomain($domainName);
        if ($request->input('q')) {
            $mailboxes = $domain->mailboxes()->where('username', $request->input('q'))->with(['domain'])->get();
        } else {
            $mailboxes = $domain->mailboxes()->with(['domain'])->get();
        }

        $data = $this->transformCollection($mailboxes);

        return $this->respond($data);
    }

    /**
     * Store a newly created mailbox.
     * Note we may automaticly create a new alias base on setting file
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $domainName
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $domainName)
    {
        $domain = $this->getDomain($domainName);

        if ($request->isMethod('patch')) {
            // need to merge with request with existing record
        } elseif ($request->isMethod('put')) {
            // need to replace the existing record??
        }
    }

    /**
     * Display the specified mailbox.
     *
     * @param  string $domainName
     * @param  int  $mailboxId
     * @return \Illuminate\Http\Response
     */
    public function show(string $domainName, int $mailboxId)
    {
        $domain = $this->getDomain($domainName);
        $mailbox = $domain->mailboxes()->with(['domain'])->findOrFail($mailboxId);

        $data = $this->transformItem($mailbox);

        return $this->respond($data);
    }

    /**
     * Update the specified mailbox.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $domainName
     * @param  int  $mailboxId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $domainName, int $mailboxId)
    {
        $domain = $this->getDomain($domainName);
    }

}