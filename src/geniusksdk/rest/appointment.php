<?php

namespace GeniusKSDK\REST;

/**
 * Access Appointment objects stored in Keap Max Classic. Appointment objects 
 * stored in Keap Pro or Keap Max are not available via these methods.
 * 
 */
class Appointment extends \GeniusKSDK\REST {

    public function __construct(\GeniusKSDK $client) {
        parent::__construct($client);
    }

    /**
     * Retrieve Appointment Model 
     * https://developer.keap.com/docs/rest/#tag/Appointment/operation/retrieveAppointmentModelUsingGET
     * 
     * Get the custom fields for the Appointment object
     * 
     * Create a Custom Field
     * https://developer.keap.com/docs/rest/#tag/Appointment/operation/createAppointmentCustomFieldUsingPOST
     * 
     * Adds a custom field of the specified type and options to the Appointment object.
     * 
     * 
     * @return stdClass Object
     */
    public function model(array $struct = null) {
        if (null === $struct) {
            return $this->client->read('/v1/appointments/model');
        }

        $values = $this->restruct($this->modelStruct(), $struct);
        return $this->client->create('/v1/appointments/model/customFields', $values);
    }

    /**
     * List Appointments
     * https://developer.keap.com/docs/rest/#tag/Appointment
     * 
     * Retrieves all appointments for the authenticated user
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function list(array $struct = null) {
        $httpQuery = $this->buildHTTPQuery($struct);
        return $this->client->read('/v1/appointments' . $httpQuery);
    }

    /**
     * Create an Appointment
     * https://developer.keap.com/docs/rest/#tag/Appointment/operation/createAppointmentUsingPOST
     * 
     * Creates a new appointment as the authenticated user
     * 
     * @param array $struct
     * @return stdClass Object
     */
    public function create(array $struct) {
        $values = $this->restruct($this->defaultStruct(), $struct);
        return $this->client->create('/v1/appointments', $values);
    }

    /**
     * Retrieve an Appointment
     * https://developer.keap.com/docs/rest/#tag/Appointment/operation/getAppointmentUsingGET
     * 
     * Retrieves a specific appointment with respect to user permissions. The 
     * authenticated user will need the "can view all records" permission for 
     * Task/Appt/Notes
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function read(int $id) {
        return $this->client->read('/v1/appointments/' . $id);
    }

    /**
     * Update an Appointment
     * https://developer.keap.com/docs/rest/#tag/Appointment/operation/updatePropertiesOnAppointmentUsingPATCH
     * 
     * Updates the provided values of a given appointment
     * 
     * Replace an Appointment 
     * https://developer.keap.com/docs/rest/#tag/Appointment/operation/updateAppointmentUsingPUT
     * Use this update() method, just pass all of the $struct fields.
     * 
     * @param int $id
     * @param array $struct
     * @return stdClass Object
     */
    public function update(int $id, array $struct) {
        $values = $this->restruct($this->defaultStruct(), $struct);
        return $this->client->update('/v1/appointments/' . $id, $values);
    }

    /**
     * Delete an Appointment 
     * https://developer.keap.com/docs/rest/#tag/Appointment/operation/deleteAppointmentUsingDELETE
     * 
     * @param int $id
     * @return stdClass Object
     */
    public function delete(int $id) {
        return $this->client->delete('/v1/appointments/' . $id);
    }

    /**
     * end_date string required <date-time>
     * 
     * start_date string required <date-time>
     * 
     * title string required
     * 
     * @return array
     */
    public function defaultStruct() {
        return array(
            'end_date' => '', // required
            'start_date' => 0, // required
            'title' => '', //
        );
    }

    /**
     * field_type string required
     *      Enum: "Currency" "Date" "DateTime" "DayOfWeek" "Drilldown" "Email" 
     *      "Month" "ListBox" "Name" "WholeNumber" "DecimalNumber" "Percent" 
     *      "PhoneNumber" "Radio" "Dropdown" "SocialSecurityNumber" "State" 
     *      "Text" "TextArea" "User" "UserListBox" "Website" "Year" "YesNo"
     * 
     * label string required
     * 
     * @return array
     */
    public function modelStruct() {
        return array(
            'field_type' => 'Text', // required
            'label' => '', // required'
        );
    }
}
