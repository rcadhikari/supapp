<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// Listing the array with Users table columns.
$wp_users_fields = array( "ID", "user_login", "user_pass", "user_nicename", "user_email","user_url", "display_name");
$wp_min_fields = array("Username", "Email");

function import_users($users = [])
{
    $saved_users = [];
    $already_exist_users = [];

    foreach ($users as $data) {
        $user_name = $data['username'];
        $user_email = $data['email'];
        $user_id = username_exists($user_name);

        if (!$user_id and email_exists($user_email) == false) {
            // To generate random password
            //$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
            $random_password = 'demo123';
            $user_id = wp_create_user($user_name, $random_password, $user_email);

            $saved_users[] = "Usename: $user_name, Email: $user_email, Password: $random_password";

        } else {
            $already_exist_users[] = "Usename: $user_name, Email: $user_email";
        }
    }

    $saved_users_list = implode('<br/>', $saved_users);
    $already_exist_users_list = implode('<br/>', $already_exist_users);

    echo "<br/><strong>Total users imported:</strong> ".count($users);
    echo "<br/><br/><strong>New users added: </strong>".count($saved_users);
    echo "<br/>$saved_users_list";

    echo "<br/><br/><strong>Already exists users:</strong> ".count($already_exist_users);
    echo "<br/>$already_exist_users_list";
}

function do_import_users_from_csv_process()
{
    global $wpdb;
    global $wp_users_fields;
    global $wp_min_fields;

    // Get the uploaded csv file data into the array.
    $tmpName = $_FILES['uploadfile']['tmp_name'];
    $csvAsArray = array_map('str_getcsv', file($tmpName));

 /*   $csvAsArray = '[["username;email;;;"],["user1;user1@supertestexampleuser12456.com;;;"],["user2;user2@supertestexampleuser12456.com;;;"],["user3;user3@supertestexampleuser12456.com;;;"],["user4;user4@supertestexampleuser12456.com;;;"],["user5;user5@supertestexampleuser12456.com;;;"],["user6;user6@supertestexampleuser12456.com;;;"],["user7;user7@supertestexampleuser12456.com;;;"],["user8;user8@supertestexampleuser12456.com;;;"],["user9;user9@supertestexampleuser12456.com;;;"],["user10;user10@supertestexampleuser12456.com;;;"],["user11;user11@supertestexampleuser12456.com;;;"],["user12;user12@supertestexampleuser12456.com;;;"],["user13;user13@supertestexampleuser12456.com;;;"],["user14;user14@supertestexampleuser12456.com;;;"],["user15;user15@supertestexampleuser12456.com;;;"],["user16;user16@supertestexampleuser12456.com;;;"],["user17;user17@supertestexampleuser12456.com;;;"],["user18;user18@supertestexampleuser12456.com;;;"],["user19;user19@supertestexampleuser12456.com;;;"],["user20;user20@supertestexampleuser12456.com;;;"],["user21;user21@supertestexampleuser12456.com;;;"]]';*/
    //$csvAsArray = json_decode($csvAsArray);
    //pm($csvAsArray1);

    // Get the column names;
    $columns = array_shift($csvAsArray);
    $users = $csvAsArray;
    $icolumns = array_filter( explode(';', array_shift($columns)) );
    $wcolumns = array_map('strtolower', $wp_min_fields);

    // Check and make sure required fields exist on the csv file.
    $required_file_exist = array_diff($icolumns, $wcolumns);
    if (count($required_file_exist) > 0) {
        $required_file_exist = is_array($required_file_exist) ? implode(',', $required_file_exist) : $required_file_exist;
        pm('Add these missing fields on your csv file: <br/> '. $required_file_exist, 1);
    }

    $users_data = [];
    foreach ($users as $user_data) {
        $values = explode(';', array_shift($user_data));

        $user_data = [];
        foreach ($icolumns as $key => $field) {
            $user_data[$field] = $values[$key];
        }
        if (count($user_data) > 0) {
            $users_data[] = $user_data;
        }
    }
    import_users($users_data);

    echo '<br><br> <strong>All users has been successfully imported.</strong>';
}

function iuc_options()
{
    // Check whether the import button clicked on not
    if( isset( $_POST['uploadfile'] ) && ( $_POST['uploadfile'] == 'Import') ):

        // Loading the upload function
        do_import_users_from_csv_process();
        pm("",1);

    endif;

    ?>
        <div class="wrap" style="text-align: center">

            <div style="text-align: center; margin: 50px auto auto 10%; width:40%; padding:20px; border: 1px solid grey;">
                <h2>Import users from CSV</h2>

                <form method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">

                    <div>
                        <input type="file" name="uploadfile" size="35"/>
                    </div>

                    <?php // Adding the hidden value for security
                    wp_nonce_field( 'iuc-import', 'iuc-nonce' );
                    ?>

                    <input class="button-primary" type="submit" name="uploadfile" value="Import"/>
                </form>

            </div>

        </div>
<?php
}

if (!function_exists('str_getcsv')) {
    function str_getcsv($input, $delimiter = ',', $enclosure = '"', $escape = '\\', $eol = '\n') {
        if (is_string($input) && !empty($input)) {
            $output = array();
            $tmp    = preg_split("/".$eol."/",$input);
            if (is_array($tmp) && !empty($tmp)) {
                while (list($line_num, $line) = each($tmp)) {
                    if (preg_match("/".$escape.$enclosure."/",$line)) {
                        while ($strlen = strlen($line)) {
                            $pos_delimiter       = strpos($line,$delimiter);
                            $pos_enclosure_start = strpos($line,$enclosure);
                            if (
                                is_int($pos_delimiter) && is_int($pos_enclosure_start)
                                && ($pos_enclosure_start < $pos_delimiter)
                            ) {
                                $enclosed_str = substr($line,1);
                                $pos_enclosure_end = strpos($enclosed_str,$enclosure);
                                $enclosed_str = substr($enclosed_str,0,$pos_enclosure_end);
                                $output[$line_num][] = $enclosed_str;
                                $offset = $pos_enclosure_end+3;
                            } else {
                                if (empty($pos_delimiter) && empty($pos_enclosure_start)) {
                                    $output[$line_num][] = substr($line,0);
                                    $offset = strlen($line);
                                } else {
                                    $output[$line_num][] = substr($line,0,$pos_delimiter);
                                    $offset = (
                                        !empty($pos_enclosure_start)
                                        && ($pos_enclosure_start < $pos_delimiter)
                                    )
                                        ?$pos_enclosure_start
                                        :$pos_delimiter+1;
                                }
                            }
                            $line = substr($line,$offset);
                        }
                    } else {
                        $line = preg_split("/".$delimiter."/",$line);

                        /*
                         * Validating against pesky extra line breaks creating false rows.
                         */
                        if (is_array($line) && !empty($line[0])) {
                            $output[$line_num] = $line;
                        }
                    }
                }
                return $output;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

?>