<?php namespace zgldh\UploadManager;


/**
 * Created by PhpStorm.
 * User: zgldh
 * Date: 2015/8/12
 * Time: 16:50
 */
class UploadValidator
{
    /**
     * 验证文件是否符合验证规则
     * @param $file
     * @param $validatorGroups array(string)
     * @return bool
     */
    public static function validate($file, $validatorGroups)
    {
        $validators = self::mergeValidators($validatorGroups);
        $rules = self::makeRules($validators);

        $data = ['upload' => $file];
        $rules = ['upload' => $rules];
        $messages = [
            'upload.min'     => trans('validation.min.file'),
            'upload.max'     => trans('validation.max.file'),
            'upload.size'    => trans('validation.size.file'),
            'upload.between' => trans('validation.between.file')
        ];
        $validator = \Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $errors = $messages->get('upload');
            throw new UploadException($errors);
        }
        return true;
    }

    private static function mergeValidators($validatorGroups)
    {
        $validators = [];
        if (is_array($validatorGroups)) {
            foreach ($validatorGroups as $validatorGroup) {
                $groupItems = config('upload.validator_groups.' . $validatorGroup);
                $validators = $validators + $groupItems;
            }
        }
        $validators = $validators + config('upload.validator_groups.common');

        return $validators;
    }

    private static function makeRules($validators)
    {
        $rules = [];
        foreach ($validators as $key => $value) {
            if ($key == null) {
                continue;
            }
            if ($value === null) {
                $rule = $key;
            } else {
                $rule = $key . ':' . $value;
            }
            $rules[] = $rule;
        }
        $rules = join('|', $rules);
        return $rules;
    }
}