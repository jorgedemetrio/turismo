<?php
defined('_JEXEC') or die;

class TurismoSiteControllerLocal extends JControllerBase
{
    public function uploadImages()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->getInt('id');
        $files = $_FILES['images'];

        // Verificar se arquivos foram enviados
        if (empty($files['name'][0])) {
            $app->enqueueMessage(JText::_('COM_TURISMO_NENHUMA_IMAGEM_ENVIADA'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=imagem_local&id=' . $id, false));
            return;
        }

        // Diretório para salvar as imagens
        $uploadDir = JPATH_SITE . '/media/com_turismo/images/';

        // Verificar se a tabela de imagens existe e criar se não existir
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)')
              ->from($db->quoteName('information_schema.tables'))
              ->where($db->quoteName('table_name') . ' = ' . $db->quote('turismo_imagens_local'));
        $db->setQuery($query);
        $tableExists = $db->loadResult();

        if (!$tableExists) {
            $query = "CREATE TABLE IF NOT EXISTS `#__turismo_imagens_local` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `local_id` INT(11) NOT NULL,
                `image_path` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->setQuery($query);
            $db->execute();
        }

        // Processar cada arquivo enviado
        foreach ($files['name'] as $key => $name) {
            // Validar se é uma imagem
            if (getimagesize($files['tmp_name'][$key]) === false) {
                $app->enqueueMessage(JText::_('COM_TURISMO_ARQUIVO_NAO_EH_IMAGEM'), 'error');
                continue; // Pular para o próximo arquivo
            }

            // Gerar UUID para o nome do arquivo
            $uuid = JFactory::getUUID();
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            $newFileName = $uuid . '.' . $extension;

            // Mover o arquivo para o diretório de upload
            $filePath = $uploadDir . $newFileName;
            if (move_uploaded_file($files['tmp_name'][$key], $filePath)) {
                // Salvar o caminho da imagem na tabela
                $query = $db->getQuery(true);
                $query->insert($db->quoteName('#__turismo_imagens_local'))
                      ->columns($db->quoteName(array('local_id', 'image_path')))
                      ->values($db->quote($id) . ', ' . $db->quote($newFileName));
                $db->setQuery($query);
                $db->execute();
            } else {
                $app->enqueueMessage(JText::_('COM_TURISMO_ERRO_AO_SALVAR_IMAGEM'), 'error');
            }
        }

        $app->enqueueMessage(JText::_('COM_TURISMO_IMAGENS_ENVIADAS_COM_SUCESSO'), 'message');
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=imagem_local&id=' . $id, false));
    }
    public function save()
    {
        $app = JFactory::getApplication();
        $data = $app->input->getPost();


        // Validação do token
        if (!JSession::checkToken('request')) {
            $app->enqueueMessage(JText::_('COM_TURISMO_TOKEN_INVALIDO'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro', false));
            return;
        }

        // Validação do reCAPTCHA
        if (empty($data['g-recaptcha-response'])) {
            $app->enqueueMessage(JText::_('COM_TURISMO_RECAPTCHA_OBRIGATORIO'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro', false));
            return;
        }

        // Validação dos campos obrigatórios
        if (empty($data['nome']) || empty($data['cep']) || empty($data['endereco']) || empty($data['numero']) || empty($data['bairro'])) {
            $app->enqueueMessage(JText::_('COM_TURISMO_CAMPOS_OBRIGATORIOS'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro', false));
            return;
        }
        

        // Validação do CNPJ
        if (!empty($data['cnpj'])) {
            if (!$this->validarCNPJ($data['cnpj'])) {
                $app->enqueueMessage(JText::_('COM_TURISMO_CNPJ_INVALIDO'), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro', false));
                return;
            }

            // Verificar se o CNPJ já está cadastrado
            if ($this->cnpjExistente($data['cnpj'], $data['id'])) {
                $app->enqueueMessage(JText::_('COM_TURISMO_CNPJ_JA_CADASTRADO'), 'error');
                $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro', false));
                return;
            }
        }

        // Capturar informações do usuário
        $userId = JFactory::getUser()->id;
        $ipUsuario = $_SERVER['REMOTE_ADDR'];
        $ipProxy = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : '';

        


        // Lógica para salvar os dados do local
        $db = JFactory::getDbo();


		$query = $db->getQuery ( true );
        $query->select(' UUID() as ID ')
            ->from(' DUAL ')
            ->setLimit(1);
        $db->setQuery ( $query );
        $uuid = $db->loadObject()->ID;


        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__turismo_locais'))
              ->columns($db->quoteName(array('id','nome', 'cep', 'endereco', 'numero', 'bairro', 'id_user_criador', 'ip_usuario', 'ip_proxy')))
              ->values($db->quote($uuid) . ', '
                . $db->quote($data['nome']) . ', '
                . $db->quote($data['cep']) . ', '
                . $db->quote($data['endereco']) . ', '
                . $db->quote($data['numero']) . ', '
                . $db->quote($data['bairro']) . ', ' 
                . $db->quote($userId) . ', ' 
                . $db->quote($ipUsuario) . ', ' 
                . $db->quote($ipProxy));
        $db->setQuery($query);
        $db->execute();

        $this->redirectBasedOnType($data['tipo'], $uuid);
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local', false));


        
    }

    public function redirectBasedOnType($type, $uuid)
    {
        switch ($type) {
            //Ramo de Hospedagem
            case 2:
            case 3:
                $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_quartos&id='.$uuid, false));
                break;
            //Ramo de Alimenticios
            case 1:
            case 4:
            case 5:
                $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_quartos&id='.$uuid, false));
                break;
            //Outros Ramos
            default:
                $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&id='.$uuid, false));
                break;
        }
        $this->redirect();
    }

    public function saveQuarto()
    {
        $app = JFactory::getApplication();
        $data = $app->input->getPost();

        // Validação dos campos obrigatórios
        if (empty($data['nome']) || empty($data['descricao']) || empty($data['preco'])) {
            $app->enqueueMessage(JText::_('COM_TURISMO_CAMPOS_OBRIGATORIOS'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_quartos', false));
            return;
        }

        // Lógica para salvar os dados do quarto
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__turismo_quartos'))
              ->columns($db->quoteName(array('nome', 'descricao', 'preco')))
              ->values($db->quote($data['nome']) . ', ' . $db->quote($data['descricao']) . ', ' . $db->quote($data['preco']));
        $db->setQuery($query);
        $db->execute();

        $app->enqueueMessage(JText::_('COM_TURISMO_QUARTO_CADASTRADO_COM_SUCESSO'), 'message');
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_quartos', false));
    }

    public function editQuarto($id)
    {
        $app = JFactory::getApplication();
        $data = $app->input->getPost();

        // Validação dos campos obrigatórios
        if (empty($data['nome']) || empty($data['descricao']) || empty($data['preco'])) {
            $app->enqueueMessage(JText::_('COM_TURISMO_CAMPOS_OBRIGATORIOS'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_quartos&id=' . $id, false));
            return;
        }

        // Lógica para editar os dados do quarto
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__turismo_quartos'))
              ->set($db->quoteName('nome') . ' = ' . $db->quote($data['nome']))
              ->set($db->quoteName('descricao') . ' = ' . $db->quote($data['descricao']))
              ->set($db->quoteName('preco') . ' = ' . $db->quote($data['preco']))
              ->where($db->quoteName('id') . ' = ' . (int) $id);
        $db->setQuery($query);
        $db->execute();

        $app->enqueueMessage(JText::_('COM_TURISMO_QUARTO_EDITADO_COM_SUCESSO'), 'message');
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_quartos', false));
    }

    public function removeQuarto($id)
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__turismo_quartos'))
              ->where($db->quoteName('id') . ' = ' . (int) $id);
        $db->setQuery($query);
        $db->execute();

        $app->enqueueMessage(JText::_('COM_TURISMO_QUARTO_REMOVIDO_COM_SUCESSO'), 'message');
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_quartos', false));
    }

    public function saveCardapio()
    {
        $app = JFactory::getApplication();
        $data = $app->input->getPost();

        // Validação dos campos obrigatórios
        if (empty($data['nome']) || empty($data['descricao']) || empty($data['preco'])) {
            $app->enqueueMessage(JText::_('COM_TURISMO_CAMPOS_OBRIGATORIOS'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_cardapio', false));
            return;
        }

        // Lógica para salvar os dados do cardápio
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->insert($db->quoteName('#__turismo_cardapio'))
              ->columns($db->quoteName(array('nome', 'descricao', 'preco')))
              ->values($db->quote($data['nome']) . ', ' . $db->quote($data['descricao']) . ', ' . $db->quote($data['preco']));
        $db->setQuery($query);
        $db->execute();

        $app->enqueueMessage(JText::_('COM_TURISMO_ITEM_CADASTRADO_COM_SUCESSO'), 'message');
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_cardapio', false));
    }

    public function removeCardapio($id)
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__turismo_cardapio'))
              ->where($db->quoteName('id') . ' = ' . (int) $id);
        $db->setQuery($query);
        $db->execute();

        $app->enqueueMessage(JText::_('COM_TURISMO_CARDAPIO_REMOVIDO_COM_SUCESSO'), 'message');
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro_cardapio', false));
    }

    public function search()
    {
        $app = JFactory::getApplication();
        $data = $app->input->getPost();

        // Lógica para registrar a consulta
        $this->registrarConsulta($data);

        // Lógica para buscar os locais
        $model = $this->getModel('Local');
        $this->items = $model->getListQuery($data);
        
        // Redirecionar para a visão de resultados
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=default', false));
    }

    private function registrarConsulta($data)
    {
        // Lógica para registrar a consulta na tabela de logs
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        // Inserir na tabela de logs
        $query->insert($db->quoteName('#__turismo_consultas'))
              ->columns($db->quoteName(array('url', 'data')))
              ->values($db->quote(JUri::getInstance()->toString()) . ', NOW()');

        $db->setQuery($query);
        $db->execute();
    }

    private function validarCNPJ($cnpj)
    {
        // Lógica para validar CNPJ
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) != 14) {
            return false;
        }

        // Implementar a lógica de validação do CNPJ
        // (Exemplo de validação)
        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * (($i % 8) + 2);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        $soma = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * (($i % 8) + 2);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return ($cnpj[12] == $digito1 && $cnpj[13] == $digito2);
    }

    private function cnpjExistente($cnpj, $id)
    {
        // Lógica para verificar se o CNPJ já está cadastrado, ignorando o ID atual
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $query->select('COUNT(*)')
              ->from($db->quoteName('#__turismo_locais'))
              ->where($db->quoteName('cnpj') . ' = ' . $db->quote($cnpj))
              ->where($db->quoteName('id') . ' != ' . $db->quote($id));

        $db->setQuery($query);
        return $db->loadResult() > 0; // Retorna true se o CNPJ já existir
    }

    public function checkCep()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $cep = $input->getString('cep');

        // Query the database to check if the CEP exists
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select(array('uf', 'cidade', 'endereco', 'bairro'))
              ->from($db->quoteName('#__turismo_ceps'))
              ->where($db->quoteName('cep') . ' = ' . $db->quote($cep));
        $db->setQuery($query);
        $result = $db->loadObject();

        // Return the result as JSON
        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(null);
        }

        $app->close();
    }
}
