<?php

require_once __DIR__.'/controllers/actions.php';

$action = new Actions();
$render = (object) json_decode(json_encode($action, JSON_PRETTY_PRINT), true);

if (strtolower($render->method) == 'post' || !empty($render->redirect)) {
    if ($render->redirect && $render->error) {
        echo $render->error;
        die();
    } else {
        header("Location: {$render->redirect}");
        die();
    }
} elseif (strtolower($render->method) == 'get') {
    $viewApp = file_get_contents('./views/app.html');
    $viewContent = file_get_contents("./views/page/{$render->view}");
    $styleContent = file_get_contents("./styles/app.css");

    $cspContent = "default-src 'none'; style-src 'nonce-EDNnf03nceIOfn39fn3e9h3sdfa'; script-src 'none'; img-src 'self' https://pngimg.com/; child-src 'none';";

    header('HTTP/1.1 200 Success');
    header("Content-Security-Policy: {$cspContent}");
    header('X-Content-Type-Options: "nosniff"');
    header('X-XSS-Protection: "1; mode=block"');
    header('X-Frame-Options: "DENY"');
    header('Strict-Transport-Security: "max-age=631138519; includeSubDomains"');

    if (strpos($viewContent, '{!!tbody!!}') !== false) {
        function htmlTableData(array $cities): string {
            $tableData = '';
            foreach($cities as $key => $name) {
                $tableData .= '<tr>';
                $tableData .= "<td>{$key}</td>";
                $tableData .= "<td>{$name}</td>";
                $tableData .= "<td><a href=\"/city/{$name}\"><button>GO</button></a></td>";
                $tableData .= "<td><form action=\"/city/{$name}/remove\" method=\"post\"><button type=\"submit\">&times;</button></form></td>";
                $tableData .= '</tr>';
            }
            return $tableData;
        }
        $viewContent = str_replace('{!!tbody!!}', htmlTableData(['paris', 'madrid', 'new-york']), $viewContent);
    }

    $viewApp = str_replace('{!!title!!}', $render->data['title'], $viewApp);
    $viewApp = str_replace('{!!styles!!}', $styleContent, $viewApp);
    $viewApp = str_replace('{!!content!!}', $viewContent, $viewApp);

    if (!empty($render->error)) {
        $viewApp = str_replace('{!!error!!}', "<span class=\"error-message\">{$render->error}</span>", $viewApp);
    } else {
        $viewApp = str_replace('{!!error!!}', '', $viewApp);
    }

    echo $viewApp;

} else {

    // ERROR
}
