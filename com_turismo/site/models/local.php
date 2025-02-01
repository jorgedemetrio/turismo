<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

/**
 * Model para manipulação de locais no frontend
 */
class TurismoModelLocal extends ItemModel
{
    /**
     * Método para auto-popular o estado do modelo
     *
     * @return  void
     */
    protected function populateState()
    {
        $app = Factory::getApplication();
        
        // Obtém o ID do local da requisição
        $id = $app->input->getInt('id');
        $this->setState('local.id', $id);

        // Carrega os parâmetros do componente
        $params = $app->getParams();
        $this->setState('params', $params);

        parent::populateState();
    }

    /**
     * Método para obter um item
     *
     * @param   integer  $pk  O id do local
     *
     * @return  mixed    Item do local ou false
     */
    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('local.id');

        if ($this->_item === null) {
            $this->_item = array();
        }

        if (!isset($this->_item[$pk])) {
            try {
                $db = $this->getDbo();
                $query = $db->getQuery(true);

                $query->select('l.*, tl.nome AS tipo_nome, tl.google_type, tc.nome AS tipo_culinaria')
                    ->from($db->quoteName('#__turismo_locais', 'l'))
                    ->join('LEFT', $db->quoteName('#__turismo_tipos_local', 'tl') . ' ON tl.id = l.tipo_local_id')
                    ->join('LEFT', $db->quoteName('#__turismo_tipo_culinaria', 'tc') . ' ON tc.id = l.tipo_culinaria_id')
                    ->where('l.id = ' . (int) $pk);

                $db->setQuery($query);
                $data = $db->loadObject();

                if (empty($data)) {
                    throw new Exception(Text::_('COM_TURISMO_ERROR_LOCAL_NOT_FOUND'), 404);
                }

                // Carrega fotos
                $data->fotos = $this->getFotos($pk);

                // Carrega cardápio se for restaurante
                if ($data->tipo_local_id == 1) { // Assumindo que 1 é o ID para restaurantes
                    $data->cardapio = $this->getCardapio($pk);
                }

                // Carrega quartos se for hotel
                if ($data->tipo_local_id == 2) { // Assumindo que 2 é o ID para hotéis
                    $data->quartos = $this->getQuartos($pk);
                }

                $this->_item[$pk] = $data;
            } catch (Exception $e) {
                $this->setError($e);
                $this->_item[$pk] = false;
            }
        }

        return $this->_item[$pk];
    }

    /**
     * Método para obter as fotos de um local
     *
     * @param   integer  $localId  O id do local
     *
     * @return  array    Array de objetos com as fotos
     */
    public function getFotos($localId)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('#__turismo_fotos'))
            ->where($db->quoteName('local_id') . ' = ' . (int) $localId)
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('ordering') . ' ASC');

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Método para obter o cardápio de um local
     *
     * @param   integer  $localId  O id do local
     *
     * @return  array    Array de objetos com os itens do cardápio
     */
    public function getCardapio($localId)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('#__turismo_cardapio_itens'))
            ->where($db->quoteName('local_id') . ' = ' . (int) $localId)
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('categoria') . ' ASC')
            ->order($db->quoteName('ordering') . ' ASC');

        $db->setQuery($query);
        $itens = $db->loadObjectList();

        // Agrupa por categoria
        $cardapio = [];
        foreach ($itens as $item) {
            if (!isset($cardapio[$item->categoria])) {
                $cardapio[$item->categoria] = (object) [
                    'nome' => $item->categoria,
                    'itens' => []
                ];
            }
            $cardapio[$item->categoria]->itens[] = $item;
        }

        return array_values($cardapio);
    }

    /**
     * Método para obter os quartos de um local
     *
     * @param   integer  $localId  O id do local
     *
     * @return  array    Array de objetos com os quartos
     */
    public function getQuartos($localId)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('#__turismo_quartos'))
            ->where($db->quoteName('local_id') . ' = ' . (int) $localId)
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('ordering') . ' ASC');

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Método para obter as avaliações de um local
     *
     * @param   integer  $localId  O id do local
     *
     * @return  array    Array de objetos com as avaliações
     */
    public function getAvaliacoes($localId)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('a.*, u.name AS nome')
            ->from($db->quoteName('#__turismo_avaliacoes', 'a'))
            ->join('LEFT', $db->quoteName('#__users', 'u') . ' ON u.id = a.user_id')
            ->where('a.local_id = ' . (int) $localId)
            ->where('a.state = 1')
            ->order('a.created DESC');

        $db->setQuery($query);
        return $db->loadObjectList();
    }

    /**
     * Método para enviar uma mensagem
     *
     * @param   array  $data  Os dados da mensagem
     *
     * @return  boolean
     */
    public function enviarMensagem($data)
    {
        $mailer = Factory::getMailer();
        $config = Factory::getConfig();
        
        // Obtém o local
        $local = $this->getItem($data['id']);
        if (!$local) {
            throw new Exception(Text::_('COM_TURISMO_ERROR_LOCAL_NOT_FOUND'));
        }

        // Configura o e-mail
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $recipient = $local->email;
        $subject = Text::sprintf('COM_TURISMO_EMAIL_SUBJECT', $local->nome);
        
        $body = Text::sprintf(
            'COM_TURISMO_EMAIL_BODY',
            $data['nome'],
            $data['email'],
            $data['telefone'],
            $data['mensagem']
        );

        // Envia o e-mail
        $mailer->setSender($sender);
        $mailer->addRecipient($recipient);
        $mailer->setSubject($subject);
        $mailer->setBody($body);
        $mailer->isHTML(true);

        return $mailer->Send();
    }

    /**
     * Método para enviar uma avaliação
     *
     * @param   array  $data  Os dados da avaliação
     *
     * @return  boolean
     */
    public function avaliar($data)
    {
        $db = $this->getDbo();
        $user = Factory::getUser();

        // Verifica se o usuário já avaliou este local
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__turismo_avaliacoes'))
            ->where($db->quoteName('local_id') . ' = ' . (int) $data['local_id'])
            ->where($db->quoteName('user_id') . ' = ' . (int) $user->id);

        $db->setQuery($query);
        if ($db->loadResult() > 0) {
            throw new Exception(Text::_('COM_TURISMO_ERROR_JA_AVALIOU'));
        }

        // Insere a avaliação
        $query = $db->getQuery(true);
        $columns = array('local_id', 'user_id', 'rating', 'comentario', 'state', 'created', 'created_by');
        $values = array(
            (int) $data['local_id'],
            (int) $user->id,
            (int) $data['rating'],
            $db->quote($data['comentario']),
            1,
            $db->quote(Factory::getDate()->toSql()),
            (int) $user->id
        );

        $query->insert($db->quoteName('#__turismo_avaliacoes'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));

        $db->setQuery($query);
        $db->execute();

        // Atualiza a média de avaliações do local
        $this->atualizarMediaAvaliacoes($data['local_id']);

        return true;
    }

    /**
     * Método para atualizar a média de avaliações de um local
     *
     * @param   integer  $localId  O id do local
     *
     * @return  boolean
     */
    protected function atualizarMediaAvaliacoes($localId)
    {
        $db = $this->getDbo();
        
        // Calcula a nova média
        $query = $db->getQuery(true)
            ->select('AVG(rating) AS media, COUNT(*) AS total')
            ->from($db->quoteName('#__turismo_avaliacoes'))
            ->where($db->quoteName('local_id') . ' = ' . (int) $localId)
            ->where($db->quoteName('state') . ' = 1');

        $db->setQuery($query);
        $result = $db->loadObject();

        // Atualiza o local
        $query = $db->getQuery(true);
        $query->update($db->quoteName('#__turismo_locais'))
            ->set($db->quoteName('avaliacao_media') . ' = ' . (float) $result->media)
            ->set($db->quoteName('total_avaliacoes') . ' = ' . (int) $result->total)
            ->where($db->quoteName('id') . ' = ' . (int) $localId);

        $db->setQuery($query);
        return $db->execute();
    }

    /**
     * Método para incrementar o contador de acessos
     *
     * @param   integer  $localId  O id do local
     *
     * @return  boolean
     */
    public function incrementarAcessos($localId)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->update($db->quoteName('#__turismo_locais'))
            ->set($db->quoteName('acessos') . ' = ' . $db->quoteName('acessos') . ' + 1')
            ->where($db->quoteName('id') . ' = ' . (int) $localId);

        $db->setQuery($query);
        return $db->execute();
    }

    /**
     * Método para registrar um acesso
     *
     * @param   array  $data  Os dados do acesso
     *
     * @return  boolean
     */
    public function registrarAcesso($data)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $columns = array('local_id', 'user_id', 'data_acesso', 'ip');
        $values = array(
            (int) $data['local_id'],
            (int) $data['user_id'],
            $db->quote(Factory::getDate()->toSql()),
            $db->quote($data['ip'])
        );

        $query->insert($db->quoteName('#__turismo_acessos'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));

        $db->setQuery($query);
        return $db->execute();
    }
}
