import React from 'react';
import {
    Platform,
    StyleSheet,
    Text,
    View,
    ImageBackground,
    Dimensions,
    TouchableOpacity,
    Image,
    Icon,
    FlatList,
    // AsyncStorage,
    BackHandler,
    I18nManager,
    PixelRatio

} from 'react-native';
import AsyncStorage from '@react-native-community/async-storage';
import { Images, Colors } from "../themes";
import { getUserProfile, updateUserFavourite } from "../constants/apis";
import Spinner from "../components/Spinner";
import _ from "lodash";
import LinearGradient from 'react-native-linear-gradient';
import { ifIphoneX } from "react-native-iphone-x-helper";
import FlatListComponent from '../components/FlatListComponent'
import { CheckBox } from 'react-native-elements'
import { ScrollView } from 'react-native-gesture-handler';
import ProgressCircle from 'react-native-progress-circle'
import CircularProgress from '../components/CircularProgress';

import { AnimatedCircularProgress } from 'react-native-circular-progress';
// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
I18nManager.forceRTL(false);
const isRTL = I18nManager.isRTL;





export default class Dashboard extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            showPopup: false,
            isLoading: true,
            fill:50,
            multipleSelectedData: [],
            checked:false,
        };

        //this.handleBackButtonClick = this.handleBackButtonClick.bind(this);

    }

   

    render() {

        return (


            <View style={styles.container}>
                <ImageBackground source={Images.screen_dashboard} resizeMode="stretch" style={styles.container}>
                <View style={styles.firstrow}>
      <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.back} style={styles.headerIcon} />
                    </TouchableOpacity>
                    
                    <View style = {{flex:1,flexDirection:'row'}}>
                    <View style={styles.profileImage}>
                    <View style={{flex:1}}>
                    <Image source={{uri : 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=2310286619004516&height=200&width=200&ext=1551702072&hash=AeSGUCIaEHdbx9z_'}}
              style={styles.profile_image_circle} />
                <View style={styles.dajboardView}><Text style={{color:'#fff',marginLeft:25}}>Dashboard</Text></View>
              </View>
              
                    </View>
                    <View style={styles.infoContainer}>
                    <Text style={styles.userFullName}>Rahul Tomar</Text>
                    {/* <Text style={styles.followerCount}>15.2K followers | Indore, India</Text> */}
                    <View style={{flexDirection:'row'}}>
                    <Image source={Images.user_icon} style={{width: 12, height: 12}}
               />
                    <Text style={styles.followerCount}>  15.2K followers | Indore, India</Text>
                    </View>
                    
                    </View>
                    </View>
                    <TouchableOpacity
                        onPress={() => this.props.navigation.navigate('Profile')}
                        style={{ position: 'absolute', right: 10, }}>
                        {/* <Image source={Images.setting} style={styles.headerIconRight} /> */}
                    </TouchableOpacity>
      </View>
               
      <View style={styles.secondrow}>
      <View style={styles.profitrow}>
      
      <View style={{flex: 1,flexDirection:'row'}}>
                <Text style={{fontSize:15,color:"#57ccd0",marginTop:5,fontFamily: 'Montserrat-Regular'}}>PROFIT</Text>

                <Image style={{ width: width/20,height: height/35,marginHorizontal:5}}
                               // source={require('../images/logo.png')}> 
                               source={Images.profit}>
                           </Image>
                </View>
      </View>

      <View style={styles.secondrow1}>

      
      <View style={{flex: 0.5,alignItems: 'flex-start'}}>
      <View style={{flexDirection:'row'}}>
                <Text style={{fontSize:30,color:"#4c4c4c",marginTop:2,fontFamily:'Montserrat-Regular'}}>510</Text>
                <Text style={{fontSize:15,color:"#4c4c4c",marginTop:18,fontFamily:'Montserrat-Regular'}}>,87</Text>
                </View>
                <View style={{borderBottomWidth:2,borderColor: "#57ccd0",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}>Total Cash</Text>
                    </View>
                </View>
               

                <View style={{flex: 0.8,alignItems: 'flex-start',alignItems:'center'}}>
      <View style={{flexDirection:'row'}}>
                <Text style={{fontSize:30,color:"#4c4c4c",marginTop:5,textAlign: 'center',fontFamily:'Montserrat-Regular'}}>510</Text>
                <Text style={{fontSize:15,color:"#4c4c4c",marginTop:18,fontFamily:'Montserrat-Regular'}}>,87</Text>
                </View>
                <View style={{borderBottomWidth:2,borderColor: "#57ccd0",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}>Cash you missed</Text>
                    </View>
                </View>



                <View style={{flex: 0.5,alignItems: 'flex-start',alignItems:'flex-end'}}>
     
                <Text style={{fontSize:30,color:"#4c4c4c",marginTop:5}}>42%</Text>
                
                
                <View style={{borderBottomWidth:2,borderColor: "#57ccd0",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}>%response</Text>
                    </View>
                </View>

      </View>
      <View style={styles.profitrow}>
      
      <View style={{flex: 1,flexDirection:'row'}}>
                <Text style={{fontSize:15,color:"#aa5667",marginTop:-10,fontFamily: 'Montserrat-Regular'}}>OFFERS</Text>

                <Image style={styles.profitimage}
                               // source={require('../images/logo.png')}> 
                               source={Images.offers}>
                           </Image>
                </View>
      </View>
      <View style={styles.secondrow2}>


      <View style={{flex: 0.5,alignItems: 'flex-start'}}>
      
                <Text style={{fontSize:30,color:"#4c4c4c"}}>10</Text>
                
                <View style={{borderBottomWidth:2,borderColor: "#aa5667",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}>Approved offers</Text>
                    </View>
                </View>


                <View style={{flex: 0.5,alignItems: 'flex-start',alignItems:'center'}}>
     
                <Text style={{fontSize:30,color:"#4c4c4c"}}>71</Text>
               
                <View style={{borderBottomWidth:2,borderColor: "#aa5667",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}>Missed offers </Text>
                    </View>
                </View>



                <View style={{flex: 0.5,alignItems: 'flex-start',alignItems:'flex-end'}}>
      
                <Text style={{fontSize:30,color:"#4c4c4c"}}>29%</Text>
                
                <View style={{borderBottomWidth:2,borderColor: "#aa5667",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}> %response </Text>
                    </View>
                </View>
      </View>
      <View style={styles.profitrow}>
      
      <View style={{flex: 1,flexDirection:'row'}}>
                <Text style={{fontSize:15,color:"#dfa33f",marginTop:-10,fontFamily: 'Montserrat-Regular'}}>RATING</Text>

                <Image style={styles.profitimage}
                               // source={require('../images/logo.png')}> 
                               source={Images.rating}>
                           </Image>
                </View>
      </View>

      <View style={styles.secondrow3}>
      <View style={{flex: 0.5,alignItems: 'flex-start'}}>
     
                <Text style={{fontSize:30,color:"#4c4c4c"}}>80%</Text>
               
               
                <View style={{borderBottomWidth:2,borderColor: "#dfa33f",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}>Rating     </Text>
                    </View>
                </View>


                <View style={{flex: 0.5,alignItems: 'flex-start',alignItems:'center'}}>
     
                <Text style={{fontSize:30,color:"#4c4c4c"}}>90%</Text>
               
                
                <View style={{borderBottomWidth:2,borderColor: "#dfa33f",paddingBottom:0}}>
                    <Text numberOfLines={1} style={{fontSize:10,color:"#fff"}}>next level percentage</Text>
                    </View>
                </View>



<View style={{flex: 0.5,alignItems: 'flex-start',alignItems:'flex-end'}}>
      
                <Text style={{fontSize:30,color:"#4c4c4c"}}>14</Text>
               
                <View style={{borderBottomWidth:2,borderColor: "#dfa33f",paddingBottom:0}}>
                    <Text style={{fontSize:10,color:"#fff"}}>Deals needs</Text>
                    </View>
                </View>

       
      </View>
         
      </View>



      <View style={styles.thirdrow}>

      <Text style={styles.thirdrow_text1}>Most Luckrative offers</Text>
      <AnimatedCircularProgress
  size={100}
  width={8}
  fill={this.state.fill}
  tintColor="#fff"
  backgroundColor="grey">
  {
    (fill) => (
      <Text style={styles.points}>
        { 
          this.state.fill 
          }
      </Text>
    )
  }
</AnimatedCircularProgress>
</View>

<View style={styles.fourthrow}>

<ImageBackground source={Images.footer_dashboard} resizeMode="stretch" style={styles.container}></ImageBackground>
</View>
                </ImageBackground>
            </View>
        );
    }
}

const styles = StyleSheet.create({
    container: {
      flex: 1,
     // backgroundColor: '#1f2041',
     height: null, 
     width: null, 
    },
  
    firstrow: {
  
      // flex: 2.2,
      height:'20%',
      flexDirection: isRTL ? 'row-reverse' : 'row',
      //backgroundColor: "#ffc857",
      //borderBottomWidth:0.5,
      borderColor: "#727171",
  
    },
  
    secondrow: {
      // flex: 4.5,
      marginTop:20,
      marginHorizontal:20,
       height:'40%',
      ...ifIphoneX(
        {
          // height: 50,
          height:'37%',
        },
        {
          // height: 55
          height:'40%', 
  
        }
      ),
      //backgroundColor: "#4b3f72"
      borderBottomWidth:0.5,
      borderColor: "#727171",
    },

    secondrow1: {
        // flex: 4.5,
        flexDirection: isRTL ? 'row-reverse' : 'row',
        marginTop:5,
        marginHorizontal:20,
         height:'25%',
        ...ifIphoneX(
          {
            // height: 50,
            height:'22%',
          },
          {
            // height: 55
            height:'25%', 
    
          }
        ),
        //backgroundColor: "#4b3f72"
        //borderBottomWidth:0.5,
        borderColor: "#727171",
      },

      secondrow2: {
        // flex: 4.5,
         flexDirection: isRTL ? 'row-reverse' : 'row',
        marginTop:5,
        marginHorizontal:20,
         height:'25%',
        ...ifIphoneX(
          {
            // height: 50,
            height:'22%',
          },
          {
            // height: 55
            height:'25%', 
    
          }
        ),
        //backgroundColor: "#4b3f72"
        //borderBottomWidth:0.5,
        borderColor: "#727171",
      },

      secondrow3: {
        // flex: 4.5,
        flexDirection: isRTL ? 'row-reverse' : 'row',
        marginTop:5,
        marginHorizontal:20,
         height:'25%',
        ...ifIphoneX(
          {
            // height: 50,
            height:'20%',
          },
          {
            // height: 55
            height:'23%', 
    
          }
        ),
        //backgroundColor: "#4b3f72"
        //borderBottomWidth:0.5,
        borderColor: "#727171",
      },
    profitrow: {
  
        //flex: 1,
        //height:'20%',
        flexDirection: isRTL ? 'row-reverse' : 'row',
        //backgroundColor: "#ffc857",
        //borderBottomWidth:0.5,
        marginHorizontal:20,
        borderColor: "#727171",
    
      },

      profitimage:{
        width: width/20,
        height: height/35,
        //marginTop:5,
        marginTop:-10,
        marginHorizontal:5
      },
  
    thirdrow: {
        height:'25%',
        ...ifIphoneX(
          {
            // height: 50,
            height:'22%',
          },
          {
            // height: 55
            height:'25%', 
    
          }
        ),
        borderBottomWidth:0.5,
        borderColor: "#727171",
    },
    thirdrow_text1:
          {
            color: "#fff",
            fontSize: 15,
            paddingBottom: 4,
            marginHorizontal:18,
            marginTop: 4,
            fontFamily: 'Montserrat-Regular',
            textAlign: 'center',
          },
  
    fourthrow: {
      // flex: 3,
      ...ifIphoneX(
        {
          // height: 50,
          height:'15%',
        },
        {
          // height: 55
          height:'15%',
  
        }
      ),
      // backgroundColor: "#19647e"
      // borderBottomWidth:1,
      // borderColor: "#fff"
    },
    headerIcon: {
      // textAlign : 'left',
      // marginLeft: 15,
      width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 25,
      height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 25,
      borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
  
      ...ifIphoneX(
        {
          // height: 50,
          marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 40 : 40 : 10,
        },
        {
          // height: 55
          marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10,
  
        }
      )
  },
  headerIconRight: {
    // textAlign : 'left',
      width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 25,
      height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 25,
      //borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
      // marginTop: 40
      // marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10, 
  
      ...ifIphoneX(
        {
          // height: 50,
          marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 40 : 40 : 10,
        },
        {
          // height: 55
          marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 10,
  
        }
      )
  },
  profileImage: {
    flex: 0.3,
    justifyContent: 'center',
    alignItems: isRTL ? 'flex-start' : 'flex-end',
      margin: 5,
      // marginTop:40,
      marginLeft: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 0 : 30, 
      paddingTop: ( Platform.OS === 'ios' ) ? 20 : 0,
  
      ...ifIphoneX(
        {
          // height: 50,
          marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 40 : 40 : 30, 
        },
        {
          // height: 55
          marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? (PixelRatio.get() === 2 ? 13 : 20) : 40 : 30, 
  
        }
      )
  },
  infoContainer: {
  flex:0.7,
  justifyContent: 'center',
  alignItems: isRTL ? 'flex-end' : 'flex-start',
  margin: 5,
  // marginTop:20,
  marginLeft: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 0 : 0 : 0, 
  paddingTop: ( Platform.OS === 'ios' ) ? 3 : 0,
  
  ...ifIphoneX(
    {
      // height: 50,
      marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 20 : 40 : 30, 
    },
    {
      // height: 55
      marginTop: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 0 : 20 : 10, 
  
    }
  )
  // marginTop:10
  },
  userFullName: {
    color: "#fff",
    fontSize: 18
  },
  followerCount: {
    color: "#fff",
    fontSize: 11,
    
  },
  
  profile_image_circle:{
    ...ifIphoneX(
      {
        // height: 50,
        width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 110 : 110 : 100, 
        height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 110 : 110 : 100, 
        borderRadius: ((Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 110 : 110 : 100)/2,
      },
      {
        // height: 55
        width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 100, 
        height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 100, 
        borderRadius: ((Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 90 : 110 : 100)/2,
  
    
      }
    ),
  
    
    position:'absolute',
    zIndex:9999,
    left:0,
    top:0
  },
  
  dajboardView:{
    backgroundColor:'#E56785',
    paddingHorizontal:13,
    paddingVertical:0,
    
    alignItems:'center',
    justifyContent:'center',
    borderRadius:8,
    
  
    ...ifIphoneX(
      {
        // height: 50,
        height:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 30 : 20,
        width:120,
        marginTop:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 75 : 70 : 65,
        left:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 75 : 75 : 60,
      },
      {
        // height: 55
        height:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 22 : 30 : 20,
        width:120,
        marginTop:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 60 : 70 : 65,
        left:(Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 55 : 75 : 60,
    
      }
    ),
    }
});