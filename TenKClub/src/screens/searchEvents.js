import React, { Component } from "react";

import _ from "lodash";
import {
  Platform,
  StyleSheet,
  FlatList,
  Text,
  Dimensions,
  // AsyncStorage,
  View,
  Image,
  TouchableOpacity,
  ScrollView,
  ToastAndroid,
  AlertIOS,
  Alert,
  ImageBackground,
  BackHandler,
  I18nManager
} from "react-native";
import AsyncStorage from '@react-native-community/async-storage';
I18nManager.forceRTL(true); 
import { Images, Colors } from "../themes";
import { getEventList } from "../constants/apis";
import Spinner from "../components/Spinner";
import { ifIphoneX } from "react-native-iphone-x-helper";
import Video from 'react-native-video';
const { height, width } = Dimensions.get("window");
const aspectRatio = height / width;
import { KeyboardAwareScrollView } from "react-native-keyboard-aware-scroll-view";

export default class searchEvents extends Component {

  static navigationOptions = {
    title: '',  // Message
    tabBarIcon: ({ focused, tintColor }) => {
     
      return <Image style={[styles.icon, { tintColor: tintColor }]} source={require('../images/icons/icon5.png')}/>;
    },
};
  constructor(props) {
    super(props);
    this.state = {
      isLoading: false,
      defaulterArray:[]
    };
    
    this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
    
    
        
  }
  

  //------exit app start------//
  componentWillMount() {
    BackHandler.addEventListener(
      "hardwareBackPress",
      this.handleBackButtonClick
    );
  }
  componentWillUnmount() {
    BackHandler.removeEventListener(
      "hardwareBackPress",
      this.handleBackButtonClick
    );
  }
  handleBackButtonClick() {
    this.props.navigation.goBack();
    // this.props.navigation.navigate("Profile"); 
    return true;
    
  }

  //------exit app end------//

  render() {

    
    return (
      
      <ImageBackground source={Images.screen_4}  resizeMode="stretch" style={styles.container}>
      <View>

        {/* <LinearGradient
          start={{ x: 0.5, y: 0.25 }}
          end={{ x: 0.5, y: 1.0 }}
          colors={[Colors.mainColor, Colors.colorStatusBar]}
          style={styles.headerView}
        >
          
          <Text style={styles.headerTitle}>Story</Text>
          
        </LinearGradient> */}
        <View style={{marginTop:0}}>
                            <Text style={styles.headerTitle}>Search</Text>
                            </View>
      </View>
      
                
      
      </ImageBackground>
      
    );
  }

  
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    width: '100%',
		height:'100%'
  },
  headerView: {
    justifyContent: "center",
    flexDirection: "row",
    alignItems: "center",
    ...ifIphoneX(
      {
        height: 50,
        marginTop: 33
      },
      {
        height: 55
      }
    ),
    backgroundColor: "#222327",
    elevation: 7,
    shadowOpacity: 7
  },
  headerTitle: {
    // marginLeft: 10,
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 16 : 18) : 18,
    color: Colors.colorWhite,
    fontWeight: "bold",
    // fontFamily: "Arimo",
    marginTop: 30,
    // alignItems: "center",
    textAlign: 'center',
    padding:10
    // flex:""
  },
  bodyView: {
    backgroundColor: "transparent"
  },
  title: {
    textAlign: "center",
    fontSize: Platform.OS === "ios" ? (aspectRatio > 1.6 ? 14 : 16) : 18,
    color: Colors.orangeColor
  },
  imageContainer:{
    
  },
  image:{
    width:455, 
    height:200
  },
  backgroundVideo: {
    position: 'absolute',
    top: 0,
    left: 0,
    bottom: 0,
    right: 0,
  },
  icon: {
    width: 28,
    height: 28,
  },
});
