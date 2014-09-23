<?php 
namespace Handler;

class Exception
{
	public static function handle($app, $e)
	{
	    $app->response->setHeader("Content-Type", "application/json")->sendHeaders();
        switch(true) {
            case $e instanceof \Exception\BadRequestException:
                $app->response->setStatusCode(400, "Bad Request");
                echo json_encode(array('detail' => $e->getMessage()));
                break;
            case $e instanceof \Exception\UnauthorizedException:
                $app->response->setStatusCode(401, "Unauthorized");
                echo json_encode(array('detail' => $e->getMessage()));
                break;
            case $e instanceof \Exception\ValidationException:
                $app->response->setStatusCode(422, "Unprocessable Entity");
                $message = $e->getMessage();
                if(empty($message)) {
                    $message = $app->getService('MessageService')->get('validationErrorsFound');
                }
                echo json_encode(array('detail' => $message, 'messages' => $e->getMessages()));
                break;
            case $e instanceof \Exception\EntityNotFoundException:
                $app->response->setStatusCode(404, "Entity Not Found");
                $message = $e->getMessage();
                if(empty($message)) {
                    $message = $app->getService('MessageService')->get('entityNotFound');
                }
                echo json_encode(array('detail' => $message));
                break;
            case $e instanceof \Exception\UrlNotFoundException:
                $app->response->setStatusCode(404, "Url Not Found");
                $message = $e->getMessage();
                if(empty($message)) {
                    $message = $app->getService('MessageService')->get('urlNotFound');
                }
                echo json_encode(array('detail' => $message));
                break;
            case $e instanceof \Exception\TokenRequired:
                $app->response->setStatusCode(499, "Token Required");
                $message = $e->getMessage();
                if(empty($message)) {
                    $message = $app->getService('MessageService')->get('tokenRequired');
                }
                echo json_encode(array('detail' => $message));
                break;
            case $e instanceof \Exception\TokenInvalid:
                $app->response->setStatusCode(498, "Token expired/invalid");
                $message = $e->getMessage();
                if(empty($message)) {
                    $message = $app->getService('MessageService')->get('tokenInvalid');
                }
                echo json_encode(array('detail' => $message));
                break;
            case $e instanceof \Exception\NoContentException:
                $app->response->setStatusCode(204, "No Content");
                break;
            default:
                $app->response->setStatusCode(500, "Internal Server Error");
                if($app->getService('ConfigService')->debug) {
                    echo json_encode(array(
                        'detail' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ));
                } else {
                    echo json_encode(array('detail' => $app->getService('MessageService')->get('error')));
                }
                break;
        }
        $app->response->send();
	}
}