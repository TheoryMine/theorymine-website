
// if you want the form to submit the values you want to change, this is how you get PHP to use them...
$name = $_REQUEST['name'];
$body = $_REQUEST['body'];


$template_tex_file = "template.tex";
$processed_tex_file = "processed.tex";

$data = file_get_contents($template_tex_file);
$data = str_replace(array(
            '***name***',
            '***body***'
            ), array(
            $name,
            $body
            ), $data
);

file_put_contents($processed_tex_file, $data);
system("pdflatex -interaction nonstopmode -output-format pdf {$processed_tex_file}");
