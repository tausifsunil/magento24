<?php
/**
 * Webkul_Grid Status Options Model.
 * @category    Webkul
 * @author      Webkul Software Private Limited
 */
namespace Webkul\Grid\Model;
 
use Webkul\Grid\Model\ResourceModel\Grid\CollectionFactory;
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $brandCollection
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $brandCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $brandCollection->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
 
    /**
     * Get data
     *
     * @return array
     */
    
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        // $items = $this->collection->getItems();
        $this->loadedData = array();
        /** @var Customer $customer */

        // past code
        /*foreach ($items as $row) {
            $rowData = $row->getData();
            if (isset($rowData['image'])) {
                $name = $rowData['image'];
                unset($rowData['image']);
            // echo "<pre>";print_r($rowData['image'][0]);die;
                $rowData['image'][0] = [
                    'name' => $name,
                    'url' => $this->getMediaUrl().$name
                ];
            }
            $this->loadedData[$row->getId()]['grid_records_columns'] = $rowData;
        
        }
        return $this->loadedData;*/

        // here added new 
        $items = $this->collection->getItems();
        foreach ($items as $blog) {

            $rowData = $blog->getData();
            if (isset($rowData['image'])) {
                $imageName = $rowData['image'];
                $rowData['image'] = [
                    [
                        'name' => $imageName,
                        'url' => $this->getMediaUrl().$imageName
                    ],
                ];
            }
            else{            
                $rowData['image'] = null;
            }
            // $demo=implode('', $rowData);
        // echo'<pre>';print_r($demo);die;
            $this->loadedData[$blog->getId()] = $rowData;
            // $this->loadedData[$blog->getId()] = $blog->getData();
        // echo'<pre>';print_r($this->loadedData);die;
        }
        // echo'<pre>';print_r($this->loadedData);die;
        // return $this->loadedData;
        return $this->loadedData;

        // this is working 
        /*$items = $this->collection->getItems();
        foreach ($items as $blog) {
            $this->loadedData[$blog->getId()] = $blog->getData();
        }
        // echo'<pre>';print_r($this->loadedData);die;
        return $this->loadedData;*/
    }

    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'codextblog/tmp/feature/';
    }
}