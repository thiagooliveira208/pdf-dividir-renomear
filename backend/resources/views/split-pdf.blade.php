<?php
    require_once('C:\www\reajuste\backend\vendor\ilovepdf\ilovepdf-php\init.php');
    include '../vendor/autoload.php';

    use Ilovepdf\Ilovepdf;
    use ZipArchive;

    $ilovepdf = new Ilovepdf('project_public_6a5f416bcb04eab96846d4d677b9dc7e_yKbZte93aa20be7f878a381f73e5e1856123e','secret_key_1a89483746e3d92e75bc087dca31772a__-Nm4bc869a252a584aae6b1916f544df90ac');
    
    // Parse pdf file and build necessary objects.
    $parser = new \Smalot\PdfParser\Parser();

    $nameFile = 'teste_arquivo.pdf';

    $nameZip = str_replace('pdf', 'zip', $nameFile);

    // Inicia a instância da classe ZipArchive
    $zip = new \ZipArchive;

    // Cria um novo arquivo .zip chamado arquivosPdf.zip
    if (file_exists('processado/arquivosPdf.zip')) unlink(realpath('processado/arquivosPdf.zip'));

    $zip->open('processado/arquivosPdf.zip', ZipArchive::CREATE);

    #### LER TODOS OS ARQUIVOS DA PASTA E RENOMEAR ####

    #### DIVIDIR ARQUIVOS PDF EM 2 PAGINAS POR ARQUIVO ####
    // Create a new task
    $myTaskSplit = $ilovepdf->newTask('split');
    // Add files to task for upload
    $file1 = $myTaskSplit->addFile($nameFile);
    // Set your tool options
    $myTaskSplit->setFixedRange(2);
    // Execute the task
    $myTaskSplit->execute();
    // Download the package files
    $myTaskSplit->download();

    ########################################################

    ##### EXTRAIR ARQUIVOS .ZIP ####
    $origem = 'output.zip';
    $destino = 'processado/';
    
    echo extractZipFile($origem,$destino);

    function extractZipFile($origem,$destino){
        
        $zipFile = new ZipArchive;
        $openFile = $zipFile->open($origem);
        
        if ($openFile === TRUE) {
            $zipFile->extractTo($destino);
            $zipFile->close();
            echo 'Arquivos extraídos com sucesso.';
        } else {
            echo 'Extração dos arquivos falhou.';
        }
    }

    ############################################################


    $dir = new DirectoryIterator('./processado');
    foreach ($dir as $item) {
        if ($item->isDot()) {
            continue;
        }


        $pdf = $parser->parseFile('./processado/'.$item->getFilename());

        $text = $pdf->getText();

        $fileName = '';
        $pChave = 'Contrato:';
        $pChave2 = 'Aniversário:';

        if (strpos($text, $pChave) !== false) {
            //PEGAR TAMANHO DA STRING PARA REMOVER PALAVRA-CHAVE
            $lenghtStr = strlen($pChave);

            //PEGAR PARTE DO TEXTO ENCONTRADO APÓS PALAVRA-CHAVE
            $fileName = substr($text, strpos($text, $pChave) + $lenghtStr, 1000);

            //PEGAR O NOME ATÉ A QUEBRA DE LINHA
            //$fileName = substr($fileName, 0, strpos($fileName, "\n") - 1 );
            $fileName = substr($fileName, 0, strpos($fileName, $pChave2));

            //substituir e por - e remover espaços
            $fileName = str_replace('e', '-', $fileName);
            $fileName = str_replace(' ', '', $fileName);

            $fileName .= '.pdf';

            rename('./processado/'.$item->getFilename(), './processado/'.$fileName);

            //ADICIONAR ARQUIVO A PASTA ZIP
            $zip->addFile( 
                // Caminho do arquivo original
                'processado/'.$fileName,
                // Novo nome do arquivo
                $fileName
            );

            echo $fileName.'<br />';
        }
    }

    // Fecha a pasta e salva o arquivo
    $zip->close();

    if(is_dir($destino))
    {
        foreach(glob("$destino*.pdf") as $arquivo)
        {
            unlink($arquivo);
            echo 'Arquivo '.$arquivo.' foi apagado com sucesso. <br />';
        }
    }
    else
    {
        echo 'A pasta não existe.';
    }
    

    #### DIVIDIR ARQUIVOS PDF EM 2 PAGINAS POR ARQUIVO ####
    /*
    // Create a new task
    $myTaskSplit = $ilovepdf->newTask('split');
    // Add files to task for upload
    $file1 = $myTaskSplit->addFile('teste_arquivo.pdf');
    // Set your tool options
    $myTaskSplit->setFixedRange(2);
    // Execute the task
    $myTaskSplit->execute();
    // Download the package files
    $myTaskSplit->download();
    */

    ##### EXTRAIR ARQUIVOS .ZIP ####
    /* 
    $origem = 'output.zip';
    $destino = 'processado/';
    echo extractZipFile($origem,$destino);

    function extractZipFile($origem,$destino){
        
        $zipFile = new ZipArchive;
        $openFile = $zipFile->open($origem);
        
        if ($openFile === TRUE) {
            $zipFile->extractTo($destino);
            $zipFile->close();
            echo 'Arquivos extraídos com sucesso.';
        } else {
            echo 'Extração dos arquivos falhou.';
        }
    }
    */