/**
 - field_rules.js
 - Rules for Form Inputs
 - Project: Corona Admin Portal
 **/

 var fieldRule = {
    admin: {
        name: {
            max: 64,
            min: 3,
            characters:true
        },
        password: {
            max: 20
        },
        email: {
            max: 64
        },
        phone:{
            min: 7,
            number: true,
        }
    },
    city: {
        name: {
            max: 128
        }
    },
    facility_tracker: {
        relieving_date: {
            gt: 0,
        }
    },
    testing_centre : {
        name : {
            max : 128,
            min : 3,
            characters:true,
        },
        address : {
            max : 512
        },
        lat : {
            min : -90,
            max : 90
        },
        lng : {
            min : -180,
            max : 180
        },
        max_of_patients_in_single_time_slot: {
            gt: 0
        },
        admin: {
            max: 128,
            min:3,
            characters:true
        },
        email: {
            max: 256
        },
        password: {
            min: 6,
            max: 12
        }
    },
    change_password: {
        password: {
            min: 6,
            max: 20
        }
    },
    parish_statistics: {
        total_tests_done: {
            min: 0
        },
        total_tests_positive: {
            min: 0
        },
        total_tests_negative: {
            min: 0
        },
        number_of_people_home_quarantined: {
            min: 0
        },
        number_of_people_hospital_quarantined: {
            min: 0
        },
        number_of_deaths: {
            min: 0
        },
        number_of_people_recovered: {
            min: 0
        },
        total_cases: {
            min: 0
        },
        total_result_waiting_cases: {
            min: 0
        },
        number_of_deaths_in_child_group: {
            min: 0
        },
        number_of_deaths_in_adult_group: {
            min: 0
        },
        number_of_deaths_in_senior_group: {
            min: 0
        }
    },
    overall_statistics: {
        number_of_cases: {
            min: 0
        },
        number_of_deaths: {
            min: 0
        },
        number_of_recovered: {
            min: 0
        }
    },
    news: {
        name: {
            max: 128
        }
    },
    prioritized_immigrant: {
        first_name: {
            max: 128
        },
        middle_name: {
            max: 128
        },
        last_name: {
            max: 128
        },
        mobile_number: {
            min: 7,
            max: 15
        },
        email: {
            max: 256
        },
        address: {
            max: 1024
        },
        requirement: {
            max: 1024
        },
        city: {
            max: 128
        },
        status: {
            min: 1,
            max: 3
        },
        emergency_contact_name: {
            max: 128
        },
        emergency_contact_mobile_number: {
            min: 7,
            max: 16
        },
        emergency_contact_email: {
            max: 256
        },
        passport_number: {
            max: 20
        }
    },
    parish_quarantine : {
        name : {
            max : 64
        },
        city : {
            max : 128
        },
        street_address : {
            max : 256
        }
    },
    quarantine_users : {
        lastname : {
            max : 128
        },
        firstname : {
            max : 128
        },
        middlename : {
            max : 128
        },
        passport_number : {
            max : 15
        },
        location_name : {
            max : 64
        },
        city : {
            max : 128
        },
        street_address : {
            max : 256
        }
    },
    screening : {
        lastname : {
            max : 128
        },
        firstname : {
            max : 128
        },
        middlename : {
            max : 128
        },
        age : {
            max : 120
        },
        address : {
            max : 512
        },
        remarks : {
            max : 512
        },
        reason : {
            max : 512
        },
    }
};
