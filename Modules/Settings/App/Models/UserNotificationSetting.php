<?php

namespace Modules\Settings\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotificationSetting extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'user_id',
        'login_email',
        'login_sms',
        'logout_email',
        'logout_sms',
        'create_transaction_email',
        'create_transaction_sms',
        'payed_transaction_email',
        'payed_transaction_sms',
        'create_withdraw_email',
        'create_withdraw_sms',
        'add_bank_email',
        'add_bank_sms',
        'pay_profit_email',
        'pay_profit_sms',
        'create_investment_email',
        'create_investment_sms',
        'create_ticket_email',
        'create_ticket_sms',
        'answer_ticket_email',
        'answer_ticket_sms',
        'store_verification_email',
        'store_verification_sms',
        'change_verification_email',
        'change_verification_sms',
    ];
}
