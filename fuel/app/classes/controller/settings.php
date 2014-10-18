<?php

class Controller_Settings extends Controller_Template
{
    public static function update($settings)
    {
        foreach($settings as $setting => $data)
        {
            
            $update_setting = Model_Setting::query()
                ->where('name',$setting)
                ->get_one();
            
            if(empty($data) === true)
            {
                $data = '';
            }
            elseif($data == 'on')
            {
                $data = true;
            }
            
            // Update setting
            if(empty($update_setting) === false)
            {
              $update_setting->data = $data;
              
            }
            // Create new setting
            else
            {
                $update_setting = Model_Setting::Forge(array(
                    'name' => $setting, 
                    'data' => $data, 
                ));
            }
            
            $update_setting->save();
        }
    }
}
