<?php

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Utilizador.php';
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

        $conta = $this->criarContaLogin($dados['email'] ?? '', $dados['nome']);
        if ($conta !== null) {
            $dados['utilizador_id'] = $conta['utilizador_id'];
        }

        $this->clienteModel->create($dados);

        if ($conta !== null) {
            set_flash('sucesso', "Cliente cadastrado com sucesso. Foi criada uma conta de acesso — email: {$dados['email']}, senha temporária: {$conta['senha_gerada']}. Comunique esta senha ao cliente; ele deve alterá-la no primeiro acesso.");
        } else {
            set_flash('sucesso', 'Cliente cadastrado com sucesso.');
        }

        return true;
    }

    public function atualizar($id, $dados, $ficheiroFoto)
    {
        $dados = sanitize_input($dados);

        if (empty($dados['nome'])) {
            set_flash('erro', 'O nome é obrigatório.');
            return false;
        }

        if (!empty($ficheiroFoto['name'])) {
            $nomeFicheiro = $this->processarUpload($ficheiroFoto);
            if ($nomeFicheiro === false) {
                return false;
            }
            $dados['foto'] = $nomeFicheiro;
        }

        // Se o cliente ainda não tinha conta de login e agora tem email preenchido, cria a conta
        $clienteAtual = $this->clienteModel->findById($id);
        $conta = null;
        if (empty($clienteAtual['utilizador_id']) && !empty($dados['email'])) {
            $conta = $this->criarContaLogin($dados['email'], $dados['nome']);
            if ($conta !== null) {
                $dados['utilizador_id'] = $conta['utilizador_id'];
            }
        }

        $this->clienteModel->update($id, $dados);

        if ($conta !== null) {
            set_flash('sucesso', "Cliente atualizado com sucesso. Foi criada uma conta de acesso — email: {$dados['email']}, senha temporária: {$conta['senha_gerada']}.");
        } else {
            set_flash('sucesso', 'Cliente atualizado com sucesso.');
        }

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
     * Cria uma conta de login (tabela utilizadores) com perfil Cliente,
     * associada ao email fornecido, com uma senha temporária gerada
     * aleatoriamente. Devolve null se o email estiver vazio ou já em uso
     * (não cria conta duplicada nem bloqueia o registo do cliente).
     */
    private function criarContaLogin($email, $nome)
    {
        if (empty($email)) {
            return null;
        }

        $utilizadorModel = new Utilizador();

        if ($utilizadorModel->findByEmail($email)) {
            return null; // já existe uma conta com este email — não duplicar
        }

        $senhaGerada = bin2hex(random_bytes(4)); // 8 caracteres, ex: "a1b2c3d4"

        $utilizadorModel->create([
            'nome'      => $nome,
            'email'     => $email,
            'senha'     => password_hash($senhaGerada, PASSWORD_DEFAULT),
            'perfil_id' => PERFIL_CLIENTE,
            'ativo'     => 1,
        ]);

        $novoUtilizador = $utilizadorModel->findByEmail($email);

        return [
            'utilizador_id' => $novoUtilizador['id'],
            'senha_gerada'  => $senhaGerada,
        ];
    }

    /**
     * Valida e move o ficheiro de foto para assets/uploads/.
     * Devolve o nome final do ficheiro, ou false em caso de erro.
     */
    private function processarUpload($ficheiro)
    {
        if (empty($ficheiro) || $ficheiro['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($ficheiro['error'] !== UPLOAD_ERR_OK) {
            set_flash('erro', 'Ocorreu um erro no upload da foto.');
            return false;
        }

        if (!is_uploaded_file($ficheiro['tmp_name'])) {
            set_flash('erro', 'Upload inválido.');
            return false;
        }

        $tiposPermitidos = ['image/jpeg', 'image/png'];
        $tipoReal = mime_content_type($ficheiro['tmp_name']);
        if (!in_array($tipoReal, $tiposPermitidos)) {
            set_flash('erro', 'A foto deve ser um ficheiro JPG ou PNG.');
            return false;
        }

        $nomeOriginal = strtolower(basename($ficheiro['name']));
        $extensaoPermitida = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
        if (!in_array($extensaoPermitida, ['jpg', 'jpeg', 'png'])) {
            set_flash('erro', 'A extensão do ficheiro não é válida.');
            return false;
        }

        $tamanhoMaximo = 2 * 1024 * 1024;
        if ($ficheiro['size'] > $tamanhoMaximo) {
            set_flash('erro', 'A foto não pode exceder 2 MB.');
            return false;
        }

        $infoImagem = getimagesize($ficheiro['tmp_name']);
        if ($infoImagem === false) {
            set_flash('erro', 'O ficheiro enviado não é uma imagem válida.');
            return false;
        }

        $extensao = $tipoReal === 'image/png' ? 'png' : 'jpg';
        $nomeFinal = uniqid('cliente_', true) . '.' . $extensao;

        $destinoDiretorio = __DIR__ . '/../assets/uploads/';
        if (!is_dir($destinoDiretorio)) {
            mkdir($destinoDiretorio, 0755, true);
        }

        $destino = $destinoDiretorio . $nomeFinal;
        if (!move_uploaded_file($ficheiro['tmp_name'], $destino)) {
            set_flash('erro', 'Não foi possível guardar a foto.');
            return false;
        }

        return $nomeFinal;
    }
}