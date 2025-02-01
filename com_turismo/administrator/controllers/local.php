<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Helper\ContentHelper;

/**
 * Controller para manipulação de locais
 *
 * @since  1.0.0
 */
class TurismoControllerLocal extends FormController
{
    protected $text_prefix = 'COM_TURISMO_LOCAL';

    public function getModel($name = 'Local', $prefix = 'TurismoModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }



    public function changeHighlight()
    {
        // Verifica o token de segurança
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Obtém o ID e o novo destaque da requisição
        $id = $this->input->getInt('id');
        $highlight = $this->input->getInt('highlight');

        // Verifica se o destaque está entre 0 e 9
        if ($highlight < 0 || $highlight > 9) {
            JError::raiseError(500, JText::_('Invalid highlight value'));
            return false;
        }

        // Carrega o modelo
        $model = $this->getModel('Local');

        // Altera o destaque
        if ($model->updateHighlight($id, $highlight)) {
            $this->setMessage(JText::_('Highlight updated successfully'));
        } else {
            $this->setMessage(JText::_('Failed to update highlight'));
        }

        // Redireciona para a lista de locais
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=locals', false));
    }

    public function changeStatus()
    {
        // Verifica o token de segurança
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        // Obtém o ID e o novo status da requisição
        $id = $this->input->getInt('id');
        $status = $this->input->getString('status');

        // Verifica se o status é válido
        $validStatuses = ['APROVADO', 'REPROVADO', 'REMOVIDO'];
        if (!in_array($status, $validStatuses)) {
            JError::raiseError(500, JText::_('Invalid status value'));
            return false;
        }

        // Carrega o modelo
        $model = $this->getModel('Local');

        // Altera o status
        if ($model->updateStatus($id, $status)) {
            $this->setMessage(JText::_('Status updated successfully'));
        } else {
            $this->setMessage(JText::_('Failed to update status'));
        }

        // Redireciona para a lista de locais
        $this->setRedirect(JRoute::_('index.php?option=com_turismo&view=locals', false));
    }



    public function save($key = null, $urlVar = null)
    {
        $app = Factory::getApplication();
        $input = $app->input;

        // Obtém os dados do formulário
        $data = $input->get('jform', array(), 'array');

        // Validação de CNPJ e CEP
        if (empty($data['cnpj']) || !preg_match('/^\d{14}$/', $data['cnpj'])) {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_CNPJ_INVALIDO'), 'error');
            return false;
        }

        if (empty($data['cep']) || !preg_match('/^\d{5}-\d{3}$/', $data['cep'])) {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_CEP_INVALIDO'), 'error');
            return false;
        }

        // Salvar os dados
        $model = $this->getModel();
        if ($model->save($data)) {
            $app->enqueueMessage(Text::_('COM_TURISMO_LOCAL_SALVO_COM_SUCESSO'), 'success');
            $this->setRedirect('index.php?option=com_turismo&view=local');
        } else {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_SALVAR_LOCAL'), 'error');
            $this->setRedirect('index.php?option=com_turismo&view=local&layout=edit&id=' . $data['id']);
        }
    }

    public function importCSV()
    {
        $app = Factory::getApplication();
        $input = $app->input;

        // Verifica se um arquivo foi enviado
        if ($_FILES['csv_file']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];
            $fileName = $_FILES['csv_file']['name'];

            // Lê o arquivo CSV
            $file = fopen($fileTmpPath, 'r');
            while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
                // Validação dos dados
                if (count($data) < 5) {
                    continue; // Ignora linhas inválidas
                }

                $localData = [
                    'nome' => $data[0],
                    'cnpj' => $data[1],
                    'cep' => $data[2],
                    'bairro' => $data[3],
                    'tipo_local_id' => $data[4],
                    // Adicione outros campos conforme necessário
                ];

                // Salva o local
                $model = $this->getModel();
                $model->save($localData);
            }
            fclose($file);
            $app->enqueueMessage(Text::_('COM_TURISMO_CSV_IMPORTADO_COM_SUCESSO'), 'success');
        } else {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_IMPORTAR_CSV'), 'error');
        }

        $this->setRedirect('index.php?option=com_turismo&view=local');
    }

    public function exportCSV()
    {
        $app = Factory::getApplication();
        $model = $this->getModel();
        $items = $model->getItems(); // Método que deve retornar os locais com base nos filtros

        // Gera o CSV
        $filename = 'locais_export.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Nome', 'CNPJ', 'CEP', 'Bairro', 'Tipo']); // Cabeçalho do CSV

        foreach ($items as $item) {
            fputcsv($output, [$item->nome, $item->cnpj, $item->cep, $item->bairro, $item->tipo_local_id]);
        }

        fclose($output);
        $app->close();
    }

    public function search()
    {
        $app = Factory::getApplication();
        $input = $app->input;

        // Obtém os filtros
        $filters = [
            'nome' => $input->get('filter_nome', '', 'STRING'),
            'cnpj' => $input->get('filter_cnpj', '', 'STRING'),
            'cep' => $input->get('filter_cep', '', 'STRING'),
            'bairro' => $input->get('filter_bairro', '', 'STRING'),
            // Adicione outros filtros conforme necessário
        ];

        // Chama o modelo para buscar os locais
        $model = $this->getModel();
        $model->setState('filter', $filters);
        $items = $model->getListQuery(); // Método que deve retornar os locais com base nos filtros

        // Exibe os resultados
        $this->setRedirect('index.php?option=com_turismo&view=local&filter=' . json_encode($filters));
    }

    public function delete($key = 'id')
    {
        $app = Factory::getApplication();
        $model = $this->getModel();

        // Obtém o ID do local a ser removido
        $ids = $app->input->get('cid', array(), 'array');

        if (empty($ids)) {
            $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_SELECIONE_LOCAIS'), 'error');
            return false;
        }

        foreach($ids as $id) {
            // Remove os locais
            if (!$model->updateStatus($id, 'REMOVIDO')) {
                $app->enqueueMessage(Text::_('COM_TURISMO_ERRO_REMOVER_LOCAL'), 'error');
                $this->setRedirect('index.php?option=com_turismo&view=local');
                return;
            }
        }
        $app->enqueueMessage(Text::_('COM_TURISMO_LOCAL_REMOVIDO_COM_SUCESSO'), 'success');

        $this->setRedirect('index.php?option=com_turismo&view=local');
    }

    public function cancel($key = 'id')
    {
        $this->setRedirect('index.php?option=com_turismo&view=local');
    }
}