// export const baseUrl = 'http://192.168.1.23/10k-club/webservices/'
/* Production URL */
// export const baseUrl = 'https://10k.tempurl.co.il/webservices/'
// https://10k.tempurl.co.il/
/* Staging URL */
export const baseUrl = 'https://10k.tempurl.co.il/staging/webservices/'

//---Login---//  
export const userLogin = baseUrl + 'first_v4.php'

export const userNormalLogin = baseUrl + 'login.php'


//----Instagram Login Via API-----//
export const userInstaLogin = baseUrl + 'login_with_instagram.php'

//---Profile---//
export const getUserProfile = baseUrl + 'get_user_profile_v2.php'
export const updateUserProfile = baseUrl + 'edit_user_profile_v2.php'
export const updateUserFavourite = baseUrl + 'edit_user_favourite.php'

//---Up coming events ---//
export const getEventList = baseUrl + 'get_story_list.php'
// export const uploadEvent = baseUrl + 'second_v2.php'
export const uploadEvent = baseUrl + 'accept_campaign_story_by_user.php'

 
//---My Events ---//
export const myEventList = baseUrl + 'get_my_event_list.php'
//---My Notifications ---//
export const myNotificationList = baseUrl + 'get_my_notification_list.php'

//---Offers U Missed ---//
export const offersUMissed = baseUrl + 'offers_you_missed_list.php'

//---Availabe Soon ---//
export const availableSoon = baseUrl + 'available_soon_list.php'

//---Made For You List ---//
export const madeForUList = baseUrl + 'made_for_you_list.php'

// Challenge required API OTP verification
export const verifyOTP = baseUrl + 'verify_cmd_v4.php'

// Get offer detail by id
export const getOfferDetailById = baseUrl + 'get_offer_detail_by_id.php'

// Update payment method
export const updatePaymentMethod = baseUrl + 'add_payment_method.php'

// payment received list
export const PaymentReceivedList = baseUrl + 'payment_received_list.php'

// forgot pass
export const UserForgotPass = baseUrl + 'user_forgotpass.php'