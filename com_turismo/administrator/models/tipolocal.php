<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Form\Form;

/**
 * Modelo de Tipo de Local
 *
 * @since  1.0.0
 */
class TurismoModelTipoLocal extends AdminModel
{
    /**
     * Método para obter o formulário
     *
     * @param   array    $data      Dados do formulário
     * @param   boolean  $loadData  Carregar dados
     *
     * @return  Form|boolean  Um objeto Form em caso de sucesso, false em caso de falha
     *
     * @since   1.0.0
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            'com_turismo.tipolocal',
            'tipolocal',
            array(
                'control' => 'jform',
                'load_data' => $loadData
            )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Método para obter os dados que devem ser injetados no formulário
     *
     * @return  mixed  Os dados para o formulário
     *
     * @since   1.0.0
     */
    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState(
            'com_turismo.edit.tipolocal.data',
            array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Método para obter uma tabela
     *
     * @param   string  $type    Nome do tipo de tabela
     * @param   string  $prefix  Prefixo da classe da tabela
     * @param   array   $config  Array de configuração
     *
     * @return  Table|boolean  Objeto Table em caso de sucesso, false caso contrário
     *
     * @since   1.0.0
     */
    public function getTable($type = 'TipoLocal', $prefix = 'TurismoTable', $config = array())
    {
        return Table::getInstance($type, $prefix, $config);
    }

    /**
     * Método para preparar o formulário
     *
     * @param   Form   $form   O formulário a ser alterado
     * @param   array  $data   Os dados associados ao formulário
     *
     * @return  boolean  True em caso de sucesso, false em caso de falha
     *
     * @since   1.0.0
     */
    protected function preprocessForm(Form $form, $data, $group = 'content')
    {
        parent::preprocessForm($form, $data, $group);
        return true;
    }
}
