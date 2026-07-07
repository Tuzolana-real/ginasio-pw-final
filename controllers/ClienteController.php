<?php

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../includes/helpers.php';

class ClienteController
{
    private $clienteModel;

    public function __construct()
    {
        $this->clienteModel = new Cliente();
    }

    public function listar()
    {
        return $this->clienteModel->findAll('nome ASC');
    }

    public function obter($id)
    {
        return $this->clienteModel->findById($id);
    }

    public function criar($dados, $ficheiroFoto)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['nome'])) {
            set_flash('erro', 'O nome é obrigatório.');
            return false;
        }

        $nomeFicheiro = $this->processarUpload($ficheiroFoto);
        if ($nomeFicheiro === false) {
            return false; // a mensagem de erro já foi definida em processarUpload()
        }

        $dados['foto'] = $nomeFicheiro;

        $this->clienteModel->create($dados);
        set_flash('sucesso', 'Cliente cadastrado com sucesso.');
        return true;
    }

    public function atualizar($id, $dados, $ficheiroFoto)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['nome'])) {
            set_flash('erro', 'O nome é obrigatório.');
            return false;
        }

        // Só processa novo upload se o utilizador escolheu um ficheiro novo
        if (!empty($ficheiroFoto['name'])) {
            $nomeFicheiro = $this->processarUpload($ficheiroFoto);
            if ($nomeFicheiro === false) {
                return false;
            }
            $dados['foto'] = $nomeFicheiro;
        }

        $this->clienteModel->update($id, $dados);
        set_flash('sucesso', 'Cliente atualizado com sucesso.');
        return true;
    }

    public function eliminar($id)
    {
        $this->clienteModel->delete($id);
        set_flash('sucesso', 'Cliente eliminado com sucesso.');
    }

    public function pesquisar($termo)
    {
        return $this->clienteModel->searchByNome($termo);
    }

    /**
     * Valida e move o ficheiro de foto para assets/uploads/.
     * Devolve o nome final do ficheiro, ou false em caso de erro.
     */
    private function processarUpload($ficheiro)
    {
        // Se não veio nenhum ficheiro, não é erro — cliente pode não ter foto
        if (empty($ficheiro) || $ficheiro['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($ficheiro['error'] !== UPLOAD_ERR_OK) {
            set_flash('erro', 'Ocorreu um erro no upload da foto.');
            return false;
        }

        $tiposPermitidos = ['image/jpeg', 'image/png'];
        $tipoReal = mime_content_type($ficheiro['tmp_name']);

        if (!in_array($tipoReal, $tiposPermitidos)) {
            set_flash('erro', 'A foto deve ser um ficheiro JPG ou PNG.');
            return false;
        }

        $tamanhoMaximo = 2 * 1024 * 1024; // 2 MB
        if ($ficheiro['size'] > $tamanhoMaximo) {
            set_flash('erro', 'A foto não pode exceder 2 MB.');
            return false;
        }

        $extensao = $tipoReal === 'image/png' ? 'png' : 'jpg';
        $nomeFinal = uniqid('cliente_', true) . '.' . $extensao;

        $destino = __DIR__ . '/../assets/uploads/' . $nomeFinal;
        if (!move_uploaded_file($ficheiro['tmp_name'], $destino)) {
            set_flash('erro', 'Não foi possível guardar a foto.');
            return false;
        }

        return $nomeFinal;
    }
}