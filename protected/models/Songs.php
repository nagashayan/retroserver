<?php

/**
 * This is the model class for table "songs".
 *
 * The followings are the available columns in table 'songs':
 * @property integer $id
 * @property integer $lang_id
 * @property integer $album1
 * @property integer $album2
 * @property integer $album3
 * @property integer $album4
 * @property integer $album5
 * @property string $song_name
 * @property string $added_on
 *
 * The followings are the available model relations:
 * @property Albums $album10
 * @property Albums $album20
 * @property Albums $album30
 * @property Albums $album40
 * @property Albums $album50
 * @property Languages $lang
 */
class Songs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'songs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lang_id, album1, song_name, added_on', 'required'),
			array('lang_id, album1, album2, album3, album4, album5', 'numerical', 'integerOnly'=>true),
			array('song_name', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lang_id, album1, album2, album3, album4, album5, song_name, added_on', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'album10' => array(self::BELONGS_TO, 'Albums', 'album1'),
			'album20' => array(self::BELONGS_TO, 'Albums', 'album2'),
			'album30' => array(self::BELONGS_TO, 'Albums', 'album3'),
			'album40' => array(self::BELONGS_TO, 'Albums', 'album4'),
			'album50' => array(self::BELONGS_TO, 'Albums', 'album5'),
			'lang' => array(self::BELONGS_TO, 'Languages', 'lang_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lang_id' => 'Lang',
			'album1' => 'Album1',
			'album2' => 'Album2',
			'album3' => 'Album3',
			'album4' => 'Album4',
			'album5' => 'Album5',
			'song_name' => 'Song Name',
			'added_on' => 'Added On',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('lang_id',$this->lang_id);
		$criteria->compare('album1',$this->album1);
		$criteria->compare('album2',$this->album2);
		$criteria->compare('album3',$this->album3);
		$criteria->compare('album4',$this->album4);
		$criteria->compare('album5',$this->album5);
		$criteria->compare('song_name',$this->song_name,true);
		$criteria->compare('added_on',$this->added_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Songs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        public function getSongs($albumlist){
            $data = Songs::model()->findAll('album1 in ('.$albumlist.') || album2 in ('.$albumlist.')'
                    . '|| album3 in ('.$albumlist.') || album4 in ('.$albumlist.') || album5 in ('.$albumlist.')');
            //$data = Songs::model()->findAll();
            $i = 0;
            $response = null;
            foreach ($data as $song){
                $response[$i] = ["id"=>$song->id,"title"=>$song->song_name,"url"=>$song->song_url];
                $i++;
            }
            return $response;
        }
}
