<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case BANK_TRANSFER = 'bank_transfer';
    case CASH = 'cash';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case MADA = 'mada';
    case APPLE_PAY = 'apple_pay';
    case STC_PAY = 'stc_pay';
    case TABBY = 'tabby';
    case TAMARA = 'tamara';
    case BKASH = 'bKash';
    case NAGAD = 'Nagad';
    case SADAD = 'sadad';

    public function label(): string
    {
        return match($this) {
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CASH => 'Cash',
            self::CREDIT_CARD => 'Credit Card',
            self::DEBIT_CARD => 'Debit Card',
            self::MADA => 'Mada',
            self::APPLE_PAY => 'Apple Pay',
            self::STC_PAY => 'STC Pay',
            self::TABBY => 'Tabby (Buy Now, Pay Later)',
            self::TAMARA => 'Tamara (Buy Now, Pay Later)',
            self::BKASH => 'bKash',
            self::NAGAD => 'Nagad',
            self::SADAD => 'SADAD',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::BANK_TRANSFER => 'bi-bank',
            self::CASH => 'bi-cash',
            self::CREDIT_CARD, self::DEBIT_CARD, self::MADA => 'bi-credit-card',
            self::APPLE_PAY => 'bi-apple',
            self::STC_PAY => 'bi-telephone',
            self::TABBY, self::TAMARA => 'bi-wallet',
            self::BKASH, self::NAGAD => 'bi-phone',
            self::SADAD => 'bi-receipt',
        };
    }
}
