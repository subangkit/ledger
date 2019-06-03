<?php
/**
 * Created by PhpStorm.
 * User: subangkit
 * Date: 2019-01-25
 * Time: 16:41
 */

namespace Blackit\Ledger;

use Money\Money;
use Money\Currency;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    protected $fillable = ['name', 'description'];

    public function accountable()
    {
        return $this->morphTo();
    }

    public function mutations()
    {
        return $this->hasMany(LedgerMutation::class);
    }

    /**
     * Returns a balance for a certain currency.
     *
     * @todo: split by currency
     */
    public function balance($currency = 'EUR'): Money
    {
        $credit = new Money(
            $this->mutations()
                ->whereDebcred(LedgerMutation::CREDIT)
                ->whereCurrency($currency)
                ->sum('amount'),
            new Currency($currency)
        );
        $debit = new Money(
            $this->mutations()
                ->whereDebcred(LedgerMutation::DEBIT)
                ->whereCurrency($currency)
                ->sum('amount'),
            new Currency($currency)
        );

        return $credit->subtract($debit);
    }
}