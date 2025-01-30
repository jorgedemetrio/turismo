<?php
defined('_JEXEC') or die;

class TurismoSiteControllerLocal extends JControllerBase
{
    public function save()
    {
        $app = JFactory::getApplication();
        $data = $app->input->getPost();

        // Validação dos campos obrigatórios
        if (empty($data['nome']) || empty($data['cep']) || empty($data['endereco']) || empty($data['numero']) || empty($data['bairro'])) {
            $app->enqueueMessage(JText::_('COM_TURISMO_CAMPOS_OBRIGATORIOS'), 'error');
            $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local&layout=cadastro', false));
            return;
        }

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

        // Lógica para salvar os dados do local
        // ...
        
        $app->enqueueMessage(JText::_('COM_TURISMO_CADASTRO_SUCESSO'), 'message');
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=local', false));
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
}
