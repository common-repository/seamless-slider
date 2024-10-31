<?php

namespace SeamlessSlider;

class API {

    private $request_method = null;

    private $allowed_methods = array('POST');

    private $received_data = array();

    private $tables = array ();

    public function __construct( $request_method ) {

        $this->request_method = $request_method;

        $this->received_data = json_decode( file_get_contents('php://input'), true );

        $this->tables = DBUpdater::$TABLES;

    }
    public function requestAllowed() {

        return in_array( $this->request_method,$this->allowed_methods );

    }
    public function superRequest() {

        return isset( $this->received_data[ Config::$AJAX['validator']['key'] ] ) && $this->received_data[ Config::$AJAX['validator']['key'] ] === Config::$AJAX['validator']['value'];

    }
    public function getRequestMethod() {

        return $this->request_method;

    }
    public function returnData() {

        $action = isset( $this->received_data['action'] ) ? $this->received_data['action'] : false;

        switch( $action ) {

            case 'getSliders':

                return $this->getSliders();

                break;
            case 'createSlider':

                return $this->createSlider();

                break;
            case 'updateSlider':

                return $this->updateSlider();

                break;
            case 'getSliderBySliderID':

                return $this->getSliderBySliderID( $this->received_data['slider_id'] );

                break;
            case 'deleteSlider':

                return $this->deleteSlider();

                break;
            case 'createSlide':

                return $this->createSlide();

                break;
            case 'createSlides':

                return $this->createSlides();

                break;
            case 'getSlidesBySliderID':

                return $this->getSlidesBySliderID( $this->received_data['parent_slider_id'] );

                break;
            case 'deleteSlide':

                return $this->deleteSlide();

                break;
            case 'getSliderWithSlides':

                return $this->getSliderWithSlidesByAlias( $this->received_data['alias'] );

                break;
            case 'updateSlidesSort':

                return $this->updateSlidesSort( $this->received_data['slides'] );

        }

        return false;

    }
    private function getSliders() {

        $result = PDOQB::start()->select('*')->from( $this->tables['ss_sliders'] )->where( array(

            'blog_id' => Config::$BLOG_ID

        ))->fetch();

        return $result->getData();

    }
    private function createSlider() {

        $return = array(
            'result' => false
        );

        if( $this->sliderExistsByAlias( $this->received_data['values']['alias'] ) ) {

            $return['error_field'] = 'alias';

            $return['failText'] = 'Such Alias already exists';

            return $return;

        }

        $values = $this->received_data['values'];

        $values['blog_id'] = Config::$BLOG_ID;

        return !PDOQB::start()->insertInto( $this->tables['ss_sliders'] )->values( $values )->fetch()->error();

    }
    private function updateSlider() {

        $return = array(
            'result' => false
        );

        $existingEntry = $this->getSliderBySliderID( $this->received_data['values']['slider_id'] );

        if( $existingEntry['alias'] !== $this->received_data['values']['alias'] && $this->sliderExistsByAlias( $this->received_data['values']['alias'] )  ) {

            $return['error_field'] = 'alias';

            $return['failText'] = 'Such Alias already exists';

            return $return;

        }

        $values = $this->received_data['values'];

        $slider_id = $values['slider_id'];

        unset( $values['slider_id'] );

        return !PDOQB::start()->update( $this->tables['ss_sliders'] )->set( $values )->where( array(
            'slider_id' => $slider_id
        ) )->fetch()->error();

    }
    private function deleteSlider() {

        $result = !PDOQB::start()->delete()->from( $this->tables['ss_sliders'] )->where( array(
            'slider_id' => $this->received_data['slider_id']
        ) )->fetch()->error();

        if( $result )
            PDOQB::start()->delete()->from( $this->tables['ss_slides'] )->where( array(
                'parent_slider_id' => $this->received_data['slider_id']
            ) )->fetch();

        return $result;

    }
    private function getSliderBySliderID( $slider_id ) {

        $result = PDOQB::start()->select('*')->from( $this->tables['ss_sliders'] )->where( array(

            'slider_id' => $slider_id

        ))->fetch();

        $data = false;

        if( !$result->error() && !$result->emptyData() ) {

            $data = $result->getData();

            $data = $data[0];

            $data['options'] = json_decode( $data['options'] );

        }

        return $data;

    }
    private function sliderExistsByAlias( $alias ) {

        $result = $this->getSliderByAlias( $alias );

        return !empty( $result );

    }
    private function getSliderByAlias( $alias ) {

        return PDOQB::start()->select('*')->from( $this->tables['ss_sliders'] )->where( array(

            'blog_id' => Config::$BLOG_ID,
            'alias' => $alias

        ))->fetch()->getData();

    }
    private function createSlide( $argValues = null ) {

        $values = $argValues ? $argValues : $this->received_data['values'];

        return !PDOQB::start()->insertInto( $this->tables['ss_slides'] )->values( $values )->fetch()->error();

    }
    private function createSlides() {

        $images = $this->received_data['images'];

        $values = $this->received_data['values'];

        foreach( $images as $image ) {

            $values['image_url'] = $image['url'];

            $values['alt'] = $image['alt'];

            $values['sort'] = $image['sort'];

            $this->createSlide( $values );

        }

        return true;

    }
    private function getSlidesBySliderID( $parent_slider_id,$unsorted = false,$sortFixed = false ) {

        $data = PDOQB::start()->select('*')->from( $this->tables['ss_slides'] )->where( array(

            'parent_slider_id' => $parent_slider_id

        ))->orderBy( 'sort' )->fetch()->getData();

        if( !$sortFixed ) {

            foreach( $data  as $row ) {

                if( $row['sort'] === '0' ) {
                    $unsorted = true;
                    break;
                }

            }

        }

        if( $unsorted ) {

            $this->fixSlidesSort( $parent_slider_id );

            return $this->getSlidesBySliderID( $parent_slider_id, false, true );

        }
        else
            return $data;

    }
    private function fixSlidesSort( $parent_slider_id ) {

        return !PDOQB::start()->raw( 'SET @a = 0; UPDATE ' . $this->tables['ss_slides'] . ' SET sort = @a:=@a+1 WHERE parent_slider_id = ' . $parent_slider_id . ' ORDER BY sort ASC' )->fetch()->error();

    }
    private function deleteSlide() {

        $deleted = !PDOQB::start()->delete()->from( $this->tables['ss_slides'] )->where( array(
            'slide_id' => $this->received_data['slide_id']
        ) )->fetch()->error();

        if( $deleted )
            return $this->fixSlidesSort( $this->received_data['slider_id'] );

        return $deleted;

    }
    private function getSliderWithSlidesByAlias( $alias ) {

        $result = PDOQB::start()->select('slider.options,slides.image_url,slides.alt,slides.sort')->from( $this->tables['ss_sliders'] )->_as('slider')
            ->rightJoin( $this->tables['ss_slides'] )->_as('slides')
            ->on(array(
                'slider.slider_id' => 'slides.parent_slider_id'
            ))
            ->where(array(
                'blog_id' => Config::$BLOG_ID,
                'alias' => $alias
            ))
            ->orderBy( 'slides.sort' )->fetch()->getData();

        if( !empty( $result ) )
            return $this->parseJoinedSliderData( $result );
        return $result;

    }
    private function parseJoinedSliderData( $data ) {

        $parsedData = array(
            'options' => null,
            'images' => array()
        );

        foreach( $data as $image_data ) {

            if( $parsedData['options'] === null )
                $parsedData['options'] = json_decode( $image_data['options'] );

            $parsedData['images'][] = array( 'url' => $image_data['image_url'],'alt' => $image_data['alt'] );

        }

        return $parsedData;

    }
    private function updateSlidesSort( $slides ) {

        $query = 'UPDATE ' . $this->tables['ss_slides'] . ' SET sort = CASE slide_id';

        foreach( $slides as $slide_id => $sort ) {

            $query .= ' WHEN ' . $slide_id . ' THEN ' . $sort;

        }

        $query .= ' END WHERE slide_id  IN ( ' . implode( array_keys( $slides ), ',' )  . ' )';

        return PDOQB::start()->raw( $query )->fetch()->error();

    }
}