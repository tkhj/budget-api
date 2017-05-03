<?php

    if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
        die();
    }

class Account extends CI_Controller {


    function __construct() {

        parent::__construct();

        // Load form helper library
        $this->load->helper('form');

        // Load form validation library
        $this->load->library('form_validation');

        // Load models
        $this->load->model('user_model');
        $this->load->model('account_model');

    }


    ////////////////////////////////////////////////////////////////////////////////
    //
    //  Check Account Name
    //
    ////////////////////////////////////////////////////////////////////////////////
    public function checkName()
    {

        // Load our form into $_POST so validation will work
        $_POST = json_decode(file_get_contents("php://input"), true);

        // Validate form fields
        $this->form_validation->set_rules(
            'accountName',
            'Account Name',
            'required|trim|strip_tags',
            array(
                'required'  => 100
            )
        );

        if ($this->form_validation->run() === FALSE)
        {

            $errorCodes = intval(strip_tags(validation_errors()));

            // Form Validation Failed, return error to user
            // TODO: figure out error codes
            $data['response'] = array(
                'error' => $errorCodes
            );

        }
        else
        {

            // Get the logged in user's id
            $user_id = $this->session->userdata('id');

            // Get the account name
            $account_name = $this->input->post('accountName');

            // query the database
            $result = $this->account_model->get_account_name($user_id, $account_name);

            if ($result)
            {

                // We found an account name for this user, return TRUE
                $data['response'] = array(
                    'nameExists' => TRUE
                );

            }
            else
            {

                // We did not find an email / password pair, return FALSE
                $data['response'] = array(
                    'nameExists' => FALSE
                );

            }

        }

        $this->load->view('api/account/checkAcctName_view', $data);

    }


    ////////////////////////////////////////////////////////////////////////////////
    //
    //  Create New Account
    //
    ////////////////////////////////////////////////////////////////////////////////
    public function create()
    {

        // Load our form into $_POST so validation will work
        $_POST = json_decode(file_get_contents("php://input"), true);

        // Validate form fields
        $this->form_validation->set_rules(
            'accountName',
            'Account Name',
            'required|trim|strip_tags',
            array(
                'required'  => 100
            )
        );

        $this->form_validation->set_rules(
            'acctBalance',
            'Account Balance',
            'required|trim|strip_tags',
            array(
                'required'  => 100
            )
        );

        if ($this->form_validation->run() === FALSE)
        {

            $errorCodes = intval(strip_tags(validation_errors()));

            // Form Validation Failed, return error to user
            // TODO: figure out error codes
            $data['response'] = array(
                'error' => $errorCodes
            );

        }
        else
        {

            // Get the logged in user's id
            $user_id = $this->session->userdata('id');

            // Get the account data
            $account_name = $this->input->post('accountName');
            $account_balance = $this->input->post('acctBalance');

			// Add data to db
            $this->account_model->account_create($user_id, $account_name, $account_balance);

            // query the database
            $result = $this->account_model->get_account_details($user_id, $account_name);

            if ($result)
            {

                // We found an account
                foreach($result as $row)
                {

                    $data['response'] = array(
                        'id'                => $row->id,
                        'accountName'       => $row->acct_name,
                        'accountBalance'    => $row->acct_balance
                    );

                }

            }
            else
            {

                // We did not find an email / password pair, return FALSE
                $data['response'] = array(
                    'accountExits' => FALSE
                );

            }

        }

        $this->load->view('api/account/createAcct_view', $data);

    }


    ////////////////////////////////////////////////////////////////////////////////
    //
    //  Create New Account
    //
    ////////////////////////////////////////////////////////////////////////////////
    public function createTransaction()
    {

        // Load our form into $_POST so validation will work
        $_POST = json_decode(file_get_contents("php://input"), true);

        // Validate form fields
        $this->form_validation->set_rules(
            'transactionName',
            'Transaction Name',
            'required|trim|strip_tags',
            array(
                'required'  => 100
            )
        );

        $this->form_validation->set_rules(
            'transactionAmount',
            'Transaction Amount',
            'required|trim|strip_tags',
            array(
                'required'  => 100
            )
        );

        $this->form_validation->set_rules(
            'transactionDate',
            'Transaction Date',
            'required|trim|strip_tags',
            array(
                'required'  => 100
            )
        );

// for use when we add tags
//        $this->form_validation->set_rules(
//            'transactionTag',
//            'Transaction Tag',
//            'trim|strip_tags'
//        );

		if ($this->form_validation->run() === FALSE)
        {

            $errorCodes = intval(strip_tags(validation_errors()));

            // Form Validation Failed, return error to user
            // TODO: figure out error codes
            $data['response'] = array(
                'error' => $errorCodes
            );

        }
        else
        {

			// Get the logged in user's id
            $user_id = $this->session->userdata('id');

            // Get the transaction data
            $transaction_name = $this->input->post('transactionName');
            $transaction_amount = $this->input->post('transactionAmount');
            $transaction_date = $this->input->post('transactionDate');
			//$transaction_tag = $this->input->post('transactionTag'); for when we add tags

            // Add data to db
            $result = $this->account_model->transaction_create($user_id, $transaction_name, $transaction_amount, $transaction_date);

            if ($result)
            {

                $data['response'] = array(
                    'success' => TRUE
                );

            }
            else
            {

                // We did not find an email / password pair, return FALSE
                $data['response'] = array(
                    'success' => FALSE
                );

            }

        }

		$this->load->view('api/account/checkAcctName_view', $data);

    }

}
