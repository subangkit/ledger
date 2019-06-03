<?php

namespace Blackit\Ledger;

trait Accountable
{
    public function accounts()
    {
        return $this->morphMany(LedgerAccount::class, 'accountable');
    }

    public function account(string $name)
    {
        return $this->accounts()->where('name', $name)->first();
    }

    public function createAccount(string $name, string $description)
    {
        $check = $this->account($name);
        if ($check != null)
            return $check;

        $account = new LedgerAccount();
        $account->name = $name;
        $account->description = $description;

        return $this->accounts()->save($account);
    }
}
