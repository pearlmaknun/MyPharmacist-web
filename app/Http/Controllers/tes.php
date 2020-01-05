<?php

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

// register user
$app->post('/register', function (Request $request, Response $response, array $args) {
    $input = $request->getParsedBody();
    $sql = "SELECT * FROM tb_user WHERE user_email= :email";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("email", $input['email']);
    $sth->execute();
    $user = $sth->fetchObject();

    // verify email address.
    if ($user) {
        return $this->response->withJson(['status' => false, 'message' => 'User with this email already registered']);
    } else {
        $sql = "INSERT INTO tb_user (user_name, user_email, user_number, user_address, user_password) VALUE (:user_name, :user_email, :user_number, :user_address, :user_password)";
        $stmt = $this->db->prepare($sql);
        $options = [
            'cost' => 10,
        ];
        $hash = password_hash($input["password"], PASSWORD_DEFAULT, $options);
        $data = [
            ":user_name" => $input["username"],
            ":user_email" => $input["email"],
            ":user_number" => $input["number"],
            ":user_address" => $input["address"],
            ":user_password" => $hash
        ];

        if ($stmt->execute($data)) {
            $sql = "SELECT user_id FROM tb_user WHERE user_email= :email";
            $sth = $this->db->prepare($sql);
            $sth->bindParam("email", $input['email']);
            $sth->execute();
            $user = $sth->fetchObject();
            if ($user){
                return $response->withJson(["status" => true, "message" => "Success", "id" => $user->user_id], 200);
            }
            return $response->withJson(["status" => false, "message" => "Registration Failed."], 200);
        }

        return $response->withJson(["status" => false, "message" => "Registration Failed."], 200);
    }
});

// login user
$app->post('/login', function (Request $request, Response $response, array $args) {
    
    $input = $request->getParsedBody();
    $sql = "SELECT * FROM tb_user WHERE user_email= :email";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("email", $input['email']);
    $sth->execute();
    $user = $sth->fetchObject();

    // verify email address.
    if (!$user) {
        return $this->response->withJson(['status' => false, 'message' => 'These credentials do not match our records.']);
    }

    // verify password.
    if (!password_verify($input['password'], $user->user_password)) {
        return $this->response->withJson(['status' => false, 'message' => 'These credentials do not match our records.']);
    }

    $settings = $this->get('settings'); // get settings array.

    $now = new DateTime();
    $datefortoken = $now->format('Y-m-d H:i:s');    // MySQL datetime format
    $token = JWT::encode(['id' => $user->user_id, 'email' => $user->user_email, 'logindate' => $datefortoken], $settings['jwt']['secret'], "HS256");

    $sql = "INSERT INTO tb_user_application (user_id, token, device_id, app_version, latitude, longitude) VALUE (:user_id, :token, :device_id, :app_version, :latitude, :longitude)";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":user_id" => $user->user_id,
        ":token" => $token,
        ":app_version" => $input["app_version"],
        ":device_id" => $input["device_id"],
        ":latitude" => $input["latitude"],
        ":longitude" => $input["longitude"]
    ];

    if ($stmt->execute($data))
        return $response->withJson(["status" => true, "message" => "success", "token" => $token, "data" => $user], 200);

    return $response->withJson(["status" => false, "message" => "failed", "data" => "0"], 200);
});

// register apoteker
$app->post('/register_apoteker', function (Request $request, Response $response, array $args) {
    $input = $request->getParsedBody();
    $sql = "SELECT * FROM tb_apoteker WHERE apoteker_email= :email";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("email", $input['email']);
    $sth->execute();
    $user = $sth->fetchObject();

    // verify email address.
    if ($user) {
        return $this->response->withJson(['status' => false, 'message' => 'User with this email already registered']);
    } else {
        $sql = "INSERT INTO tb_apoteker (apoteker_name, apoteker_nik,  apoteker_email, apoteker_number, apoteker_address, apoteker_password, status) VALUE (:apoteker_name, :apoteker_nik, :apoteker_email, :apoteker_number, :apoteker_address, :apoteker_password, '1')";
        $stmt = $this->db->prepare($sql);
        $options = [
            'cost' => 10,
        ];
        $hash = password_hash($input["password"], PASSWORD_DEFAULT, $options);
        $data = [
            ":apoteker_name" => $input["username"],
            ":apoteker_nik" => $input["nik"],
            ":apoteker_email" => $input["email"],
            ":apoteker_number" => $input["number"],
            ":apoteker_address" => $input["address"],
            ":apoteker_password" => $hash
        ];

        if ($stmt->execute($data)) {
            $sql = "SELECT apoteker_id FROM tb_apoteker WHERE apoteker_email= :email";
            $sth = $this->db->prepare($sql);
            $sth->bindParam("email", $input['email']);
            $sth->execute();
            $user = $sth->fetchObject();
            if ($user){
                return $response->withJson(["status" => true, "message" => "success", "id" => $user->apoteker_id], 200);
            }
            return $response->withJson(["status" => false, "message" => "Registration Failed."], 200);
        }

        return $response->withJson(["status" => false, "message" => "Registration Failed."], 200);
    }
});

// aktivasi akun apoteker
$app->post('/aktivasi', function (Request $request, Response $response, array $args) {
    //$input = $request->getParsedBody();
    $sql = "SELECT * FROM tb_apoteker WHERE apoteker_email= :email";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("email", $request->getQueryParam("email"));
    $options = [
            'cost' => 10,
        ];
    $hash = password_hash($request->getQueryParam("password"), PASSWORD_DEFAULT, $options);
    $sth->execute();
    $user = $sth->fetchObject();

    // verify email address.
    if ($user) {
        if($user->status == '0'){
            $sql = "UPDATE tb_apoteker SET status=:st, apoteker_password=:password WHERE apoteker_id=:apoteker_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":apoteker_id" => $user->apoteker_id, ":st" => '1', ":password" => $hash]);
            return $response->withJson(["status" => true, "message" => "success", "username" => $user->apoteker_name, "id" => $user->apoteker_id], 200);
        }
        return $this->response->withJson(['status' => false, 'message' => 'User with this email already registered']);
    } else {
        return $this->response->withJson(['status' => false, 'message' => 'User with this email does not registered']);
    }
});

// login apoteker
$app->post('/login_apoteker', function (Request $request, Response $response, array $args) {

    $input = $request->getParsedBody();
    $sql = "SELECT * FROM tb_apoteker WHERE apoteker_email= :email";
    $sth = $this->db->prepare($sql);
    $sth->bindParam("email", $input['email']);
    $sth->execute();
    $user = $sth->fetchObject();

    // verify email address.
    if (!$user) {
        return $this->response->withJson(['status' => false, 'message' => 'These credentials do not match our records.']);
    }

    // verify password.
    if (!password_verify($input['password'], $user->apoteker_password)) {
        return $this->response->withJson(['status' => false, 'message' => 'These password do not match our records.']);
    }
    
    if ($user->status == '0'){
        return $this->response->withJson(['status' => false, 'message' => 'Akun belum di aktivasi.']);
    }
    
    if ($user->status == '2'){
        return $this->response->withJson(['status' => false, 'message' => 'Akun di suspend karena melakukan pelanggaran.']);
    }

    $settings = $this->get('settings'); // get settings array.

    $now = new DateTime();
    $datefortoken = $now->format('Y-m-d H:i:s');    // MySQL datetime format
    $token = JWT::encode(['id' => $user->apoteker_id, 'email' => $user->apoteker_email, 'logindate' => $datefortoken], $settings['jwt']['secret'], "HS256");

    $sql = "INSERT INTO tb_apoteker_application (apoteker_id, token, device_id, app_version, latitude, longitude) VALUE (:apoteker_id, :token, :device_id, :app_version, :latitude, :longitude)";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":apoteker_id" => $user->apoteker_id,
        ":token" => $token,
        ":app_version" => $input["app_version"],
        ":device_id" => $input["device_id"],
        ":latitude" => $input["latitude"],
        ":longitude" => $input["longitude"]
    ];

    if ($stmt->execute($data))
        return $response->withJson(["status" => true, "message" => "success", "token" => $token, "data" => $user], 200);

    return $response->withJson(["status" => false, "message" => "failed", "data" => "0"], 200);
});

// hash
$app->get('/hash', function (Request $request, Response $response, array $args) {
    $input = $request->getParsedBody();

    $options = [
        'cost' => 10,
    ];
    $hash = password_hash($input['text'], PASSWORD_DEFAULT, $options);
    return $this->response->withJson(['hash' => $hash]);
});

$app->group('/api', function (\Slim\App $app) {

    $app->get('/profile', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "SELECT * FROM tb_user WHERE user_id=:user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":user_id" => $result->user_id]);
            $result = $stmt->fetchObject();
            return $response->withJson(["status" => true, "message" => "success", "data" => $result], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->post('/request', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "INSERT INTO tb_chat (user_id, apoteker_id) VALUE (:user_id, :apoteker_id)";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([":user_id" => $result->user_id, ":apoteker_id" => $request->getQueryParam("apoteker_id")])){
                $sql = "UPDATE tb_apoteker_application SET status_konsultasi=:st WHERE apoteker_id=:apoteker_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":apoteker_id" => $request->getQueryParam("apoteker_id"), ":st" => '0']);
                return $response->withJson(["status" => true, "message" => "success", "status_konsultasi" => 0], 200);
            }
            return $response->withJson(["status" => false, "message" => "something went wrong", "status_konsultasi" => 0], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/statuscon', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "SELECT * FROM tb_chat WHERE user_id = :id AND end_chat IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $result->user_id]);
            if ($stmt->rowCount() > 0){
                $result = $stmt->fetchObject();
                return $response->withJson(["status" => true, "message" => "success", "data" => $result], 200);
            }
            return $response->withJson(["status" => false, "message" => "no activity"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });

    $app->get('/apotek', function (Request $request, Response $response, array $args) {
        $lat = $request->getQueryParam("latitude");
        $lng = $request->getQueryParam("longitude");
        $rad = $request->getQueryParam("radius");
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            $sql = "SELECT *, (6371 * ACOS(SIN(RADIANS(apotik_latitude)) * SIN(RADIANS(:latitude)) + COS(RADIANS(apotik_longitude - :longitude)) * COS(RADIANS(apotik_latitude)) * COS(RADIANS(:latitude)))) AS jarak FROM tb_apotik ORDER BY jarak ASC";
            //2 * 3961 * asin(sqrt((sin(radians((lat2 - lat1) / 2))) ^ 2 + cos(radians(lat1)) * cos(radians(lat2)) * (sin(radians((lon2 - lon1) / 2))) ^ 2))
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("latitude", $lat);
            $stmt->bindParam("longitude", $lng);
            $stmt->bindParam("radius", $rad);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $response->withJson(["status" => true, "message" => "success",  "data" => $result], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/apoteker', function (Request $request, Response $response, array $args) {
        $lat = $request->getQueryParam("latitude");
        $lng = $request->getQueryParam("longitude");
        $rad = $request->getQueryParam("radius");
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            //$sql = "SELECT a.*, (6371 * ACOS(SIN(RADIANS(b.latitude)) * SIN(RADIANS(:lat)) + COS(RADIANS(b.longitude - :lng)) * COS(RADIANS(b.latitude)) * COS(RADIANS(:lat)))) AS jarak FROM tb_apoteker a INNER JOIN tb_apoteker_application b ON a.apoteker_id = b.apoteker_id WHERE b.status_konsultasi = '1' HAVING jarak < :rad ORDER BY jarak ASC";
            $sql = "SELECT a.*, 2 * 6.371 * asin(sqrt( power((sin(radians((b.latitude - :lat) / 2))) , 2) + cos(radians(b.latitude)) * cos(radians(:lat)) * power((sin(radians((b.longitude - :lng) / 2))) , 2) )) AS jarak FROM tb_apoteker a INNER JOIN tb_apoteker_application b ON a.apoteker_id = b.apoteker_id WHERE b.status_konsultasi = '1' HAVING jarak < :rad ORDER BY jarak ASC";
            //$sql = "SELECT *, (6371 * ACOS(SIN(RADIANS(latitude)) * SIN(RADIANS(:latitude)) + COS(RADIANS(longitude - :longitude)) * COS(RADIANS(latitude)) * COS(RADIANS(:latitude)))) AS jarak FROM tb_apoteker_application HAVING jarak < :radius ORDER BY jarak ASC";
            ////2 * 6371 * asin(sqrt((sin(radians((lat2 - lat1) / 2))) ^ 2 + cos(radians(lat1)) * cos(radians(lat2)) * (sin(radians((lon2 - lon1) / 2))) ^ 2))
//             2 * 6371 * asin(sqrt( power((sin(radians((b.latitude – :lat) / 2))) , 2) + cos(radians(:lat)) * cos(radians(b.latitude)) * power((sin(radians((b.longitude – :lng) / 2))) , 2) )) as distance
// from
// the_data;
            $stmt = $this->db->prepare($sql);
            /*$stmt->bindParam("latitude", $lat);
            $stmt->bindParam("longitude", $lng);
            $stmt->bindParam("radius", $rad);*/
            $stmt->bindParam("lat", $lat);
            $stmt->bindParam("lng", $lng);
            $stmt->bindParam("rad", $rad);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $response->withJson(["status" => true, "message" => "success",  "data" => $result], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/logout', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "DELETE FROM tb_user_application WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $result->id]);
            return $response->withJson(["status" => true, "message" => "success"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->post('/endchat/{id}', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        $id = $args["id"];
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $datetimenow = date('Y-m-d H:m:s');
            $sql = "UPDATE tb_chat SET status_chat=5, end_chat=:datetimenow WHERE chat_id=:id AND end_chat is NULL";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([":id" => $id, ":datetimenow" => $datetimenow])){
                return $response->withJson(["status" => true, "message" => "success"], 200);
            }
            return $response->withJson(["status" => false, "message" => "no activity"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->post('/report/{id}', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        $id = $args["id"];
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $datetimenow = date('Y-m-d H:m:s');
            $sql = "UPDATE tb_chat SET status_chat=6, end_chat=:datetimenow, pelapor=2 WHERE chat_id=:id AND end_chat is NULL";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([":id" => $id, ":datetimenow" => $datetimenow])){
                return $response->withJson(["status" => true, "message" => "success"], 200);
            }
            return $response->withJson(["status" => false, "message" => "no activity"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->post('/ratechat/{id}', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        $chatId = $args["id"];
        if ($stmt->rowCount() > 0) {
            $datetimenow = date('Y-m-d H:m:s');
            $sql = "UPDATE tb_chat SET rating_star=:star, rating_comment=:comment, rating_time=:time WHERE chat_id=:id";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([":id" => $chatId, ":time" => $datetimenow, ":star" => $request->getQueryParam("star"), ":comment" => $request->getQueryParam("comment")])){
                return $response->withJson(["status" => true, "message" => "success"], 200);
            }
            return $response->withJson(["status" => false, "message" => "no activity"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/history', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "SELECT * FROM `tb_chat` WHERE user_id=:id AND status_chat != '0' AND status_chat != '1'";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([":id" => $result->user_id])){
                $data = $stmt->fetchAll();
                return $response->withJson(["status" => true, "message" => "success", "data" => $data], 200);
            }
            return $response->withJson(["status" => false, "message" => "no activity"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->put('/expired/{id}', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_user_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        $id = $args["id"];
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $datetimenow = date('Y-m-d H:m:s');
            $sql = "UPDATE tb_chat SET status_chat=4, end_chat=:datetimenow WHERE chat_id=:id AND end_chat is NULL";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([":id" => $id, ":datetimenow" => $datetimenow])){
                //$result = $stmt->fetchObject();
                return $response->withJson(["status" => true, "message" => "success"], 200);
            }
            return $response->withJson(["status" => false, "message" => "no activity"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
});

$app->group('/apoteker', function (\Slim\App $app) {

    $app->get('/profile', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "SELECT * FROM tb_apoteker WHERE apoteker_id=:apoteker_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":apoteker_id" => $result->apoteker_id]);
            $data = $stmt->fetchObject();
            return $response->withJson(["status" => true, "message" => "success", "status_konsultasi" => $result->status_konsultasi, "data" => $data], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/resume', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "SELECT AVG(rating_star) 'average_rating' FROM tb_chat WHERE status_chat = '4' AND apoteker_id=:apoteker_id";
            $stmt = $this->db->prepare($sql);
            if($stmt->execute([":apoteker_id" => $result->apoteker_id])){
                $avg = $stmt->fetchObject();
                $sql = "SELECT * FROM tb_chat WHERE end_chat BETWEEN NOW() - INTERVAL 7 DAY AND NOW() AND apoteker_id=:apoteker_id ORDER BY end_chat DESC";
                $stmt = $this->db->prepare($sql);
                if($stmt->execute([":apoteker_id" => $result->apoteker_id])){
                    $data = $stmt->rowCount();
                    return $response->withJson(["status" => true, "message" => "success", "average_rating" => $avg->average_rating, "totalweek" => $data], 200);
                }
                return $response->withJson(["status" => false, "message" => "Failed"], 200);
            }
            return $response->withJson(["status" => false, "message" => "Failed"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/logout', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "DELETE FROM tb_apoteker_application WHERE id=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $result->id]);
            return $response->withJson(["status" => true, "message" => "success"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->put('/statuskonsultasi/{id}', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $id = $args["id"];
        $status = $request->getQueryParam("status");
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "UPDATE tb_apoteker_application SET status_konsultasi=:st WHERE apoteker_id=:id";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":id" => $id,
                ":st" => $status,
            ];
            if ($stmt->execute($data))
                return $response->withJson(["status" => true, "message" => "success"], 200);
            return $response->withJson(["status" => false, "message" => "failed"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->put('/lokasiapoteker/{id}', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $id = $args["id"];
        $lat = $request->getQueryParam("latitude");
        $lng = $request->getQueryParam("longitude");
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "UPDATE tb_apoteker_application SET latitude=:lat, longitude=:lng WHERE apoteker_id=:id";
            $stmt = $this->db->prepare($sql);
            $data = [
                ":id" => $id,
                ":lat" => $lat,
                ":lng" => $lng
            ];
            if ($stmt->execute($data))
                return $response->withJson(["status" => true, "message" => "success"], 200);
            return $response->withJson(["status" => false, "message" => "failed"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/statuscon', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "SELECT * FROM tb_chat WHERE apoteker_id = :id AND end_chat IS NULL AND (status_chat = '0' OR status_chat = '1')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([":id" => $result->apoteker_id]);
            if ($stmt->rowCount() > 0){
                $data = $stmt->fetchObject();
                $sql = "SELECT user_id, user_name, user_email, user_number FROM tb_user WHERE user_id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([":id" => $data->user_id]);
                $user = $stmt->fetchObject();
                return $response->withJson(["status" => true, "message" => "success", "data" => $data, "user" => $user], 200);
            }
            return $response->withJson(["status" => false, "message" => "no activity"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->put('/statuschat', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $status = $request->getQueryParam("status");
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            if ($status == '1'){
                $sql = "UPDATE tb_chat SET status_chat=:st, start_chat = now() WHERE apoteker_id=:id AND end_chat is NULL";
            } else {
                $sql = "UPDATE tb_chat SET status_chat=:st WHERE apoteker_id=:id AND end_chat is NULL";
            }
            $stmt = $this->db->prepare($sql);
            // $sql2 = "SELECT user_id, user_name FROM tb_user WHERE user_id = :id_user";
            // $stmt2 = $this->db->prepare($sql);
            // $sql3 = "SELECT apoteker_id, apoteker_name FROM tb_user WHERE apoteker_id = :id_apoteker";
            // $stmt3 = $this->db->prepare($sql);
            $data = [
                ":id" => $result->apoteker_id,
                ":st" => $status,
            ];
            if ($stmt->execute($data)){
                $sql = "SELECT * FROM tb_chat WHERE apoteker_id=:id AND end_chat is NULL";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([":id" => $result->apoteker_id])){
                    $konsultasi = $stmt->fetchObject();
                    return $response->withJson(["status" => true, "message" => "success", "konsultasi" => $konsultasi], 200);
                }
                return $response->withJson(["status" => false, "message" => "failed"], 200);
            }
            return $response->withJson(["status" => false, "message" => "failed"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });
    
    $app->get('/history', function (Request $request, Response $response, array $args) {
        $bearer = $request->getHeader("Authorization");
        $did = $request->getHeader("device_id")[0];
        $token = substr($bearer[0], 7);
        $status = $request->getQueryParam("status");
        $sql = "SELECT * FROM tb_apoteker_application WHERE token=:token AND device_id=:device_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":token" => $token, ":device_id" => $did]);
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchObject();
            $sql = "SELECT * FROM `tb_chat` WHERE apoteker_id=:id AND status_chat != '0' AND status_chat != '1'";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute([":id" => $result->apoteker_id])){
                $data = $stmt->fetchAll();
                return $response->withJson(["status" => true, "message" => "success", "data" => $data], 200);
            }
            return $response->withJson(["status" => false, "message" => "failed"], 200);
        }
        return $response->withJson(["status" => false, "message" => "Unauthorized"], 200);
    });

    /*$app->put("/statuskonsultasi/{id}", function (Request $request, Response $response, $args) {
        $id = $args["id"];
        $status = $request->getQueryParam("status");
        $sql = "UPDATE tb_apoteker_application SET status_konsultasi=:st WHERE apoteker_id=:id";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id" => $id,
            ":st" => $status,
        ];

        if ($stmt->execute($data))
            return $response->withJson(["status" => "success", "data" => "1"], 200);

        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });*/
});