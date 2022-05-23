<?php
/**
 * Created by PhpStorm.
 * Author: hlh XueSi
 * Email: 1592328848@qq.com
 * Date: 2022/5/23 15:09:04
 */
declare(strict_types=1);

namespace LavaMusic\GenerateCode\Templates;

use App\Model\LavaBaseModel;

/**
 * XxxModel
 * Class XxxModel
 * Create With ClassGeneration
 */
class XxxModel extends LavaBaseModel
{
    /**
     * Description: 新增数据
     * Author: LavaMusic
     * Date: 2022/05/23 14:57:04
     */
    public function addData(array $data)
    {
        // todo:: 新增数据
        return $this->_save($data);
    }


    /**
     * Description: 更新数据
     * Author: LavaMusic
     * Date: 2022/05/23 14:57:04
     */
    public function updateData(array $where, array $data)
    {
        // todo:: 更新数据
        return $this->where($where)->update($data);
    }


    /**
     * Description: 获取一条数据
     * Author: LavaMusic
     * Date: 2022/05/23 14:57:04
     */
    public function getOne(array $where)
    {
        // todo:: 获取一条数据
        return $this->where($where)->get();
    }


    /**
     * Description: 获取数据列表
     * Author: LavaMusic
     * Date: 2022/05/23 14:57:04
     */
    public function getList(array $where)
    {
        // todo:: 获取数据列表
        return $this->where($where)->all();
    }


    /**
     * Description: 删除数据
     * Author: LavaMusic
     * Date: 2022/05/23 14:57:04
     */
    public function deleteData(array $where)
    {
        // todo:: 删除数据
        return $this->delete($where);
    }
}