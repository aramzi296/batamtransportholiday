<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingEvent extends Model
{
    protected $fillable = [
        'booking_id',
        'event_type',
        'description',
        'metadata',
        'user_id',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the booking that owns the event
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who triggered the event (if manual action)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Event type constants
     */
    const TYPE_CUSTOMER_SUBMIT = 'customer_submit';
    const TYPE_EMAIL_SENT_CUSTOMER = 'email_sent_customer';
    const TYPE_EMAIL_SENT_ADMIN = 'email_sent_admin';
    const TYPE_WHATSAPP_SENT_ADMIN = 'whatsapp_sent_admin';
    const TYPE_STATUS_CHANGED = 'status_changed';
    const TYPE_EMAIL_CONFIRMATION_SENT = 'email_confirmation_sent';
    const TYPE_WHATSAPP_CONFIRMATION_SENT = 'whatsapp_confirmation_sent';
}
