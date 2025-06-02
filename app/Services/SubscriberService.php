<?php

namespace App\Services;


use Exception;
use App\Models\Subscriber;
use App\Mail\SubscriberMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SubscriberRequest;
use App\Http\Requests\SubscriberEmailRequest;


class SubscriberService
{

    protected array $subscriberCateFilter = [
        'email',
    ];

    protected array $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Subscriber::where(function ($query) use ($requests) {
                if (isset($requests['from_date']) && isset($requests['to_date'])) {
                    $first_date = Date('Y-m-d', strtotime($requests['from_date']));
                    $last_date  = Date('Y-m-d', strtotime($requests['to_date']));
                    $query->whereDate('created_at', '>=', $first_date)->whereDate(
                        'created_at',
                        '<=',
                        $last_date
                    );
                }
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->subscriberCateFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Subscriber $subscriber): void
    {
        try {
            $subscriber->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @param SubscriberRequest $request
     * @return Subscriber
     * @throws Exception
     */
    public function store(SubscriberRequest $request): Subscriber
    {
        try {
            $subscriber        = new Subscriber;
            $subscriber->email = $request->email;
            $subscriber->save();
            return $subscriber;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @param SubscriberEmailRequest $request
     * @return void
     * @throws Exception
     */
    public function sendEmail(SubscriberEmailRequest $request)
    {
        try {
            $subscribers = Subscriber::select('email')->get();
            
            if ($subscribers->isEmpty()) {
                throw new Exception(trans('all.message.no_subscribers_found'));
            }
            
            // Convert collection to array of email addresses
            $emailAddresses = $subscribers->pluck('email')->toArray();
            
            // Send emails in smaller batches to avoid timeouts
            $chunks = array_chunk($emailAddresses, 20);
            
            foreach ($chunks as $chunk) {
                Mail::bcc($chunk)->send(new SubscriberMail($request->subject, $request->message));
            }
            
            return true;
        } catch (Exception $exception) {
            Log::error('Subscriber email error: ' . $exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
