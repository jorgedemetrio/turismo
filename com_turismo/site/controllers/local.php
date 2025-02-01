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
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;

/**
 * Controller para manipulação de locais no frontend
 */
class TurismoControllerLocal extends FormController
{
    /**
     * Método para enviar mensagem de contato
     *
     * @return  void
     */
    public function enviarMensagem()
    {
        // Verifica o token
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $app = Factory::getApplication();
        $input = $app->input;
        $model = $this->getModel('Local');

        // Obtém os dados do formulário
        $data = [
            'id' => $input->getInt('id'),
            'nome' => $input->getString('nome'),
            'email' => $input->getString('email'),
            'telefone' => $input->getString('telefone'),
            'mensagem' => $input->getString('mensagem')
        ];

        // Valida o captcha
        $captcha = JFactory::getConfig()->get('captcha');
        if ($captcha) {
            $namespace = 'turismo_contato_' . $data['id'];
            try {
                JPluginHelper::importPlugin('captcha');
                $dispatcher = JEventDispatcher::getInstance();
                $res = $dispatcher->trigger('onCheckAnswer', $input->getString('g-recaptcha-response', ''));
                if (!$res[0]) {
                    throw new Exception(Text::_('COM_TURISMO_ERRO_CAPTCHA'));
                }
            } catch (Exception $e) {
                $app->enqueueMessage($e->getMessage(), 'error');
                $this->setRedirect(Route::_('index.php?option=com_turismo&view=local&layout=detalhes&id=' . $data['id'], false));
                return;
            }
        }

        try {
            // Envia a mensagem
            if ($model->enviarMensagem($data)) {
                $app->enqueueMessage(Text::_('COM_TURISMO_MENSAGEM_ENVIADA'), 'success');
            } else {
                throw new Exception(Text::_('COM_TURISMO_ERRO_ENVIAR_MENSAGEM'));
            }
        } catch (Exception $e) {
            $app->enqueueMessage($e->getMessage(), 'error');
        }

        // Redireciona de volta para a página de detalhes
        $this->setRedirect(Route::_('index.php?option=com_turismo&view=local&layout=detalhes&id=' . $data['id'], false));
    }

    /**
     * Método para enviar avaliação
     *
     * @return  void
     */
    public function avaliar()
    {
        // Verifica o token
        Session::checkToken() or die(Text::_('JINVALID_TOKEN'));

        $app = Factory::getApplication();
        $input = $app->input;
        $user = Factory::getUser();
        $model = $this->getModel('Local');

        // Verifica se o usuário está logado
        if ($user->guest) {
            $app->enqueueMessage(Text::_('COM_TURISMO_LOGIN_PARA_AVALIAR'), 'warning');
            $returnUrl = base64_encode(Uri::getInstance()->toString());
            $this->setRedirect(Route::_('index.php?option=com_users&view=login&return=' . $returnUrl, false));
            return;
        }

        // Obtém os dados do formulário
        $data = [
            'local_id' => $input->getInt('id'),
            'user_id' => $user->id,
            'rating' => $input->getInt('rating'),
            'comentario' => $input->getString('comentario')
        ];

        try {
            // Envia a avaliação
            if ($model->avaliar($data)) {
                $app->enqueueMessage(Text::_('COM_TURISMO_AVALIACAO_ENVIADA'), 'success');
            } else {
                throw new Exception(Text::_('COM_TURISMO_ERRO_ENVIAR_AVALIACAO'));
            }
        } catch (Exception $e) {
            $app->enqueueMessage($e->getMessage(), 'error');
        }

        // Redireciona de volta para a página de detalhes
        $this->setRedirect(Route::_('index.php?option=com_turismo&view=local&layout=detalhes&id=' . $data['local_id'], false));
    }

    /**
     * Método para registrar acesso
     *
     * @return  void
     */
    public function registrarAcesso()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $user = Factory::getUser();
        $model = $this->getModel('Local');

        $localId = $input->getInt('id');

        try {
            // Incrementa o contador de acessos
            $model->incrementarAcessos($localId);

            // Se o usuário estiver logado, registra o acesso
            if (!$user->guest) {
                $data = [
                    'local_id' => $localId,
                    'user_id' => $user->id,
                    'ip' => $_SERVER['REMOTE_ADDR']
                ];
                $model->registrarAcesso($data);
            }

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

        $app->close();
    }

    /**
     * Método para obter dados do local em formato JSON
     *
     * @return  void
     */
    public function getLocalJson()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $model = $this->getModel('Local');

        $localId = $input->getInt('id');

        try {
            $item = $model->getItem($localId);
            
            // Prepara os dados para o Google Data Search
            $data = [
                '@context' => 'https://schema.org',
                '@type' => $item->google_type,
                'name' => $item->nome,
                'description' => $item->descricao,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $item->endereco . ', ' . $item->numero,
                    'addressLocality' => $item->cidade,
                    'addressRegion' => $item->uf,
                    'postalCode' => $item->cep,
                    'addressCountry' => 'Brasil'
                ]
            ];

            // Adiciona coordenadas se disponíveis
            if ($item->latitude && $item->longitude) {
                $data['geo'] = [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude
                ];
            }

            // Adiciona tipo de culinária se disponível
            if ($item->tipo_culinaria) {
                $data['servesCuisine'] = $item->tipo_culinaria;
            }

            // Adiciona avaliações se disponíveis
            if ($item->total_avaliacoes > 0) {
                $data['aggregateRating'] = [
                    '@type' => 'AggregateRating',
                    'ratingValue' => $item->avaliacao_media,
                    'reviewCount' => $item->total_avaliacoes
                ];
            }

            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }

        $app->close();
    }
}
