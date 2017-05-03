<?php

class Account_model extends CI_Model {

    public function __construct()
    {

    }


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Get Account Name
    //
    ////////////////////////////////////////////////////////////////////////////////
    public function get_account_name($user_id, $account_name)
    {
        $this->db->select('acct_name');
        $this->db->from('accounts');
        $this->db->where('acct_owner_id', $user_id);
        $this->db->where('acct_name', $account_name);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() === 1)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Get Account Details
    //
    ////////////////////////////////////////////////////////////////////////////////
    public function get_account_details($user_id, $account_name)
    {
        $this->db->select('id, acct_name, acct_balance');
        $this->db->from('accounts');
        $this->db->where('acct_owner_id', $user_id);
        $this->db->where('acct_name', $account_name);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() === 1)
        {
            return $query->result();
        }
        else
        {
            return FALSE;
        }
    }


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Create Account
    //
    ////////////////////////////////////////////////////////////////////////////////
    public function account_create($user_id, $account_name, $account_balance)
    {
        $data = array (
            'acct_owner_id' => $user_id,
            'acct_name'     => $account_name,
            'acct_balance'  => $account_balance
        );
        return $this->db->insert('accounts', $data);
    }


    ////////////////////////////////////////////////////////////////////////////////
    //
    // Create Transaction
    //
    ////////////////////////////////////////////////////////////////////////////////
    public function transaction_create($user_id, $transaction_name, $transaction_amount, $transaction_date)
    {
        $data = array (
            'trans_name'    => $transaction_name,
            'trans_amount'  => $transaction_amount,
            'owner_id'      => $user_id,
            'trans_date'    => $transaction_date,
        );
        return $this->db->insert('transactions', $data);
    }

}
