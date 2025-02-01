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
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;
use Joomla\Registry\Registry;

/**
 * HTML View class para o componente Turismo
 */
class TurismoViewLocal extends HtmlView
{
    protected $item;
    protected $form;
    protected $avaliacoes;
    protected $params;
    protected $state;
    protected $fotos;
    protected $cardapio;

    /**
     * Display the view
     *
     * @param   string  $tpl  The name of the template file to parse
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->params = $app->getParams('com_turismo');

        // Verifica se é a página de detalhes
        if ($this->getLayout() === 'detalhes') {
            // Incrementa o contador de acessos
            $this->incrementarAcessos();

            // Registra o acesso se o usuário estiver logado
            if (!$user->guest) {
                $this->registrarAcesso($user->id);
            }

            // Carrega dados adicionais
            $this->avaliacoes = $this->get('Avaliacoes');
            $this->fotos = $this->get('Fotos');
            $this->cardapio = $this->get('Cardapio');

            // Carrega o formulário de contato com captcha
            $this->form = Form::getInstance('contactform', JPATH_COMPONENT . '/models/forms/contato.xml');
            $this->form->setFieldAttribute('captcha', 'namespace', 'turismo_contato_' . $this->item->id);

            // Adiciona os metadados do Google Data Search
            $this->addGoogleDataSearch();
            $this->prepareDocument();
        }

        // Verifica por erros
        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode("\n", $errors), 500);
        }

        // Prepara o documento
           

        parent::display($tpl);
    }

    /**
    * Prepara o documento
    *
    * @return  void
    */
    protected function prepareDocument()
    {
        $app = Factory::getApplication();
        $menus = $app->getMenu();
        $pathway = $app->getPathway();
        $title = null;

        // Adiciona links para os assets
        HTMLHelper::_('jquery.framework');
        HTMLHelper::_('bootstrap.framework');

        // Adiciona os metadados
        if ($this->item) {
            $this->document->setTitle($this->item->nome);
            $this->document->setMetaData('description', strip_tags($this->item->descricao));

            // Adiciona o caminho no breadcrumb
            if ($menu = $menus->getActive()) {
                $pathway->addItem($this->item->nome);
            }

            // Adiciona meta tags do Open Graph
            $this->document->setMetaData('og:title', $this->item->nome);
            $this->document->setMetaData('og:description', strip_tags($this->item->descricao));
            $this->document->setMetaData('og:type', 'website');
            $this->document->setMetaData('og:url', Uri::current());

            // Adiciona a primeira foto como imagem do Open Graph se disponível
            if (!empty($this->fotos)) {
                $this->document->setMetaData('og:image', Uri::root() . $this->fotos[0]->arquivo);
            }

            // Adiciona meta tags do Twitter Card
            $this->document->setMetaData('twitter:card', 'summary_large_image');
            $this->document->setMetaData('twitter:title', $this->item->nome);
            $this->document->setMetaData('twitter:description', strip_tags($this->item->descricao));
            if (!empty($this->fotos)) {
                $this->document->setMetaData('twitter:image', Uri::root() . $this->fotos[0]->arquivo);
            }
        }

        // Adiciona os scripts e estilos necessários
        $wa = $this->document->getWebAssetManager();
        $wa->useScript('com_turismo.site')
            ->useStyle('com_turismo.site');

        // Adiciona o Google Maps API se a chave estiver configurada
        $googleMapsKey = $this->params->get('google_maps_key');
        if ($googleMapsKey && $this->item->latitude && $this->item->longitude) {
            $this->document->addScript(
                "https://maps.googleapis.com/maps/api/js?key={$googleMapsKey}",
                [],
                ['defer' => true]
            );
        }

        // Adiciona o jQuery Mask se necessário
        if ($this->params->get('use_masks', 1)) {
            $wa->useScript('com_turismo.jquery.mask');
        }
    }

    /**
    * Verifica se o usuário tem acesso ao item
    *
    * @return  boolean
    */
    protected function checkAccess()
    {
        $user = Factory::getUser();
        
        // Verifica se o item está publicado
        if ($this->item->state == 0 && !$user->authorise('core.edit.state', 'com_turismo')) {
            return false;
        }

        // Verifica se o usuário tem permissão para ver o item
        if (!$user->authorise('core.view', 'com_turismo')) {
            return false;
        }

        return true;
    }

    /**
     * Adiciona os metadados do Google Data Search
     *
     * @return  void
     */
    protected function addGoogleDataSearch()
    {
        $doc = Factory::getDocument();
        
        // Prepara os dados do local
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $this->item->google_type,
            'name' => $this->item->nome,
            'description' => $this->item->descricao,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->item->endereco . ', ' . $this->item->numero,
                'addressLocality' => $this->item->cidade,
                'addressRegion' => $this->item->uf,
                'postalCode' => $this->item->cep,
                'addressCountry' => 'Brasil'
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $this->item->latitude,
                'longitude' => $this->item->longitude
            ]
        ];

        // Adiciona tipo de culinária se existir
        if (!empty($this->item->tipo_culinaria)) {
            $schema['servesCuisine'] = $this->item->tipo_culinaria;
        }

        // Adiciona avaliações se existirem
        if ($this->item->total_avaliacoes > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $this->item->avaliacao_media,
                'reviewCount' => $this->item->total_avaliacoes
            ];
        }

        // Adiciona fotos se existirem
        if (!empty($this->fotos)) {
            $schema['image'] = array_map(function($foto) {
                return Uri::root() . $foto->arquivo;
            }, $this->fotos);
        }

        // Adiciona cardápio se existir
        if (!empty($this->cardapio)) {
            $schema['hasMenu'] = [
                '@type' => 'Menu',
                'hasMenuSection' => array_map(function($categoria) {
                    return [
                        '@type' => 'MenuSection',
                        'name' => $categoria->nome,
                        'hasMenuItem' => array_map(function($item) {
                            return [
                                '@type' => 'MenuItem',
                                'name' => $item->nome,
                                'description' => $item->descricao,
                                'price' => $item->preco
                            ];
                        }, $categoria->itens)
                    ];
                }, $this->cardapio)
            ];
        }

        // Adiciona o script JSON-LD ao documento
        $doc->addCustomTag('<script type="application/ld+json">' . json_encode($schema) . '</script>');
    }

    /**
     * Incrementa o contador de acessos do local
     *
     * @return  void
     */
    protected function incrementarAcessos()
    {
        if ($this->item && $this->item->id) {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__turismo_locais'))
                ->set($db->quoteName('acessos') . ' = ' . $db->quoteName('acessos') . ' + 1')
                ->where($db->quoteName('id') . ' = ' . (int) $this->item->id);
            $db->setQuery($query);
            $db->execute();
        }
    }

    /**
     * Registra o acesso do usuário logado
     *
     * @param   int  $userId  ID do usuário
     *
     * @return  void
     */
    protected function registrarAcesso($userId)
    {
        if ($this->item && $this->item->id) {
            $db = Factory::getDbo();
            $query = $db->getQuery(true)
                ->insert($db->quoteName('#__turismo_acessos'))
                ->columns($db->quoteName(['local_id', 'user_id', 'data_acesso', 'ip']))
                ->values(
                    (int) $this->item->id . ',' .
                    (int) $userId . ',' .
                    $db->quote(Factory::getDate()->toSql()) . ',' .
                    $db->quote($_SERVER['REMOTE_ADDR'])
                );
            $db->setQuery($query);
            $db->execute();
        }
    }
}
