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
    AlertIOS,
    ToastAndroid,
    Alert
    
  } from 'react-native';
import AsyncStorage from '@react-native-community/async-storage';
import { Images, Colors } from "../themes";
import { getUserProfile, updateUserFavourite } from "../constants/apis";
import Spinner from "../components/Spinner";
import _ from "lodash";
import LinearGradient from 'react-native-linear-gradient';
import { ifIphoneX } from "react-native-iphone-x-helper";

// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;
I18nManager.forceRTL(false); 
const isRTL = I18nManager.isRTL;

const data = [
  { key: 'A' }, { key: 'B' }, { key: 'C' }, { key: 'D' }, { key: 'E' }, { key: 'F' }, { key: 'G' }, { key: 'H' }, { key: 'I' }, { key: 'J' },
  // { key: 'K' },
  // { key: 'L' },
];

const formatData = (data, numColumns) => {
  const numberOfFullRows = Math.floor(data.length / numColumns);

  let numberOfElementsLastRow = data.length - (numberOfFullRows * numColumns);
  while (numberOfElementsLastRow !== numColumns && numberOfElementsLastRow !== 0) {
    data.push({ key: `blank-${numberOfElementsLastRow}`, empty: true });
    numberOfElementsLastRow++;
  }

  return data;
};

const numColumns = 3;
export default class Favourites extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
          showPopup: false,
          isLoading: true,
          userData: {},
          FavouritesData: [],
          multipleSelectedData: [],
        };
        
        this.handleBackButtonClick = this.handleBackButtonClick.bind(this);
                 
      }

   getUserAndFavouriteDetail = ()=>{
       
        // multipleData = [];
    AsyncStorage.multiGet(['username', 'password']).then((data) => {
          let username = data[0][1];
         // let password = data[1][1];
        //   username = 'mohan.das.99990';
          let formBody = "username="+username;
          // setTimeout(() => {
          //   this.setState({ isLoading: false });
          // }, 10000);
          if (username !== null && username !== ''){
            
          fetch(
                // 'http://192.168.1.23/10k-club/webservices/get_user_profile.php',
                // 'http://10k.tempurl.co.il/webservices/get_user_profile.php',
                getUserProfile,
                {
                  method:'POST',
                  headers: {
                    Authorization: "Bearer token",
                    "Content-Type": "application/x-www-form-urlencoded"
                    // Accept: 'application/json',
                    // 'Content-Type': 'application/json',
                  },
                body: formBody,
              
              })
        
        .then((response)=> response.json())
        
        .then((res) => {
            // alert("hhh");
            
          if(res.status === 'true'){
          let responseJson = res.favourites.map(item => {
            //   alert(item.favourite_id);
                item.isSelect = false;
                item.selectedClass = styles.list;
                let s = res.user_details.favourites_id;
                let match = s.split(',')
                // console.log(match)
                for (let a in match)
                {
                    let variable = match[a]
                    // console.log(variable)
                    if(item.favourite_id == variable ){
                        this.selectItem(item);
                    }
                }
              return item;
            });
        //    alert(JSON.stringify(res.favourites[0].favourite_name));
            
            // for(let i=0;i<res.favourites.length; i++){
            //   multipleData.push(res.favourites[i].favourite_name);
            // }
            // let genderText = '';
            // if(res.user_details.gender == 1){
            //   genderText = 'Male';
            // } else if(res.user_details.gender == 1) {
            //   genderText = 'Female';
            // }else{
            //   genderText = 'Not Specified';
            // }
            // for(let i=0;i<res.user_details.favourites.length; i++){
            //   this._singleTapMultipleSelectedButtons(res.user_details.favourites[i]);
            //   // multipleData.push(res.favourites[i].favourite_name);
            // }
            
            this.setState({ 
              userData: res.user_details,
              FavouritesData: responseJson
            });
            this.setState({ isLoading: false });
            // this._singleTapMultipleSelectedButtons(res.user_details.favourites);
            //this.setState.multipleSelectedData.includes(res.user_details.favourites);
          }
          else {
            this.setState({ isLoading: false });
            alert('Favourites not found.',res.message);
          }
        
        
        })
        .catch(error => {
            this.setState({ isLoading: false });
            console.log(error);
          })
        
         .done();
      }
    });
        
        }

        // FlatListItemSeparator = () => <View style={styles.line} />;

selectItem = data => {
//   alert(JSON.stringify(data));
//   return false;
data.isSelect = !data.isSelect;
data.selectedClass = data.isSelect?
              styles.selected: styles.list;
const index = this.state.FavouritesData.findIndex(
  item => data.favourite_id === item.favourite_id
);

this.state.FavouritesData[index] = data;
this._singleTapMultipleSelectedFavourites(data);
// alert(JSON.stringify(this.state.FavouritesData[index]));
this.setState({
    FavouritesData: this.state.FavouritesData,
});
// alert(JSON.stringify(this.state.FavouritesData[index]));
// console.log(JSON.stringify(this.state.FavouritesData));
};

_singleTapMultipleSelectedFavourites(interest) {
    // alert(interest);
    if (this.state.multipleSelectedData.includes(interest)) {
      _.remove(this.state.multipleSelectedData, ele => {
        return ele === interest;
      });
    } else {
      this.state.multipleSelectedData.push(interest);
    }

    this.setState({
      multipleSelectedData: this.state.multipleSelectedData
    });

    console.log(JSON.stringify(this.state.multipleSelectedData));
  }

  async doUserFavouriteUpdate() {
    // alert(this.state.multipleSelectedData.length);
    if(this.state.multipleSelectedData.length === 0){
      
        Platform.select({
          ios: () => {
            AlertIOS.alert("Please select at least one.");
          },
          android: () => {
            ToastAndroid.show("Please select at least one.", ToastAndroid.LONG, ToastAndroid.BOTTOM);
          }
        })();
        return false;
  
    }
    // alert("y");
    // return false;
    this.setState({ isLoading: true });
    // let fcmToken = await AsyncStorage.getItem('fcmToken');
    let details = {
      
    //   first_name: this.state.firstName,
    //   last_name: this.state.lastName,
    //   user_name: this.state.userData.user_name,
      user_id: this.state.userData.user_id,
    //   email: this.state.Email,
    //   gender: this.state.eventType,
    //   age: this.state.Age,
      favourites: JSON.stringify(this.state.multipleSelectedData),
      
    };
    
    let formBody = [];
    for (let property in details) {
      let encodedKey = encodeURIComponent(property);
      let encodedValue = encodeURIComponent(details[property]);
      formBody.push(encodedKey + "=" + encodedValue);
    }
    formBody = formBody.join("&");
    console.log(formBody, "--------");
    // alert(formBody);
    // setTimeout(() => {
    //   this.setState({ isLoading: false });
    // }, 10000);
    await fetch(
      // "http://192.168.1.23/10k-club/webservices/edit_user_profile.php/",
      // 'http://10k.tempurl.co.il/webservices/edit_user_profile.php',
      updateUserFavourite,
      {
        method: "POST",
        headers: {
          Authorization: "Bearer token",
          "Content-Type": "application/x-www-form-urlencoded"
        },
        body: formBody
      }
    )
      .then(response => response.json())
      .then(responseData => {
          
        this.setState({ isLoading: false });
        // alert(JSON.stringify(responseData));
        // this.props.navigation.navigate("storyList");
        // this.props.navigation.navigate("Home");
        this.props.navigation.navigate('Tabs');
        
      })
      .catch(error => {
        this.setState({ isLoading: false });
        console.log(error);
      });
  }
        componentWillMount() {
            //alert(isRTL);
            this.getUserAndFavouriteDetail();
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
            this.props.navigation.goBack(null);
            return true;            
          }

  renderItem = ({ item, index }) => {
    if (item.empty === true) {
      return <View style={[styles.item, styles.itemInvisible]} />;
    }
    return (
        
      <View
        style={styles.item}
      >
      <TouchableOpacity
         
    onPress={() => this.selectItem(item)} 
  >
        <View style={[styles.list, item.selectedClass]} >
        {item.isSelect == true && 
        <View>
        <Image source={Images.tick_icon}
       style={{width:30,height:30,color: '#7CFC00', zIndex:9999, textAlign:'center', marginTop:0, position:'absolute', marginLeft:30}} 
        />
            <Image source={{uri : item.favourite_image}}
            style={{width: 90, height: 90, borderRadius: 90/2 , borderColor: '#7CFC00', borderWidth:1, opacity:0.5}} /> 
            </View>
        }
        {item.isSelect == false && 
       <Image source={{uri : item.favourite_image}}
              style={{width: 90, height: 90, borderRadius: 90/2 }} />
              
        }
        <Text style={styles.itemText}>{item.favourite_name}</Text>
        </View>
        </TouchableOpacity>
      </View>
      
    );
  };

//   renderItem = data =>
//   <TouchableOpacity
//     style={[styles.list, data.item.selectedClass]}      
//     onPress={() => this.selectItem(data)}
//   >
//   <Image
//     source={{ uri: data.item.thumbnailUrl }}
//     style={{ width: 40, height: 40, margin: 6 }}
//   />
//   <Text style={styles.lightText}>  {data.item.title.charAt(0).toUpperCase() + data.item.title.slice(1)}  </Text>
// </TouchableOpacity>

  render() {
    const itemNumber = this.state.FavouritesData.filter(item => item.isSelect).length;
    return (
      

      <View style={styles.container}>
      <ImageBackground source={Images.screen_6}  resizeMode="stretch" style={styles.container}>
      <View style={styles.firstrow}>
      {/* <TouchableOpacity
                        onPress={() => this.props.navigation.goBack()}
                        >
                        <Image source={Images.back} style={styles.headerIcon} />
                    </TouchableOpacity> */}
                 
                    <View style={styles.profileImage}>
                    <Image source={{uri : this.state.userData.profile_pic_url}}
              style={{width: 80, height: 80, borderRadius: 80/2}} />
              
                    </View>
                    <View style={styles.infoContainer}>
                    <Text style={styles.userFullName}>{this.state.userData.full_name}</Text>
                    <View style={{flexDirection:'row'}}>
                    <Image source={Images.user_icon} style={{width: 15, height: 15,textAlign:'left'}}
               />
                    <Text style={styles.followerCount}> {this.state.userData.follower_count} followers | {this.state.userData.city}, {this.state.userData.state}</Text>
                    </View>
                    
                    </View>

                    <TouchableOpacity
                        onPress={() => this.props.navigation.navigate('Settings')}
                        style={{ position: 'absolute', right: 10, }}>
                        
                        <Image source={Images.setting} style={styles.headerIconRight} />
                        
                        </TouchableOpacity>
                    
      </View>
      <View style={styles.secondrow}>
      {this.state.FavouritesData.length !== 0 &&
    //   <FlatList
    //     data={formatData(this.state.FavouritesData, numColumns)}
    //     style={styles.GridContainer}
    //     renderItem={this.renderItem}
    //     numColumns={numColumns}/>
    <FlatList
     data={formatData(this.state.FavouritesData, numColumns)}
     style={styles.GridContainer}
    // ItemSeparatorComponent={this.FlatListItemSeparator}
    renderItem={item => this.renderItem(item)}
    numColumns={numColumns}
    keyExtractor={item => item.favourite_id.toString()}
    extraData={this.state}
   />
      }
      </View>
      <View style={styles.thirdrow}>
        {/* Next Button : start */}
                
                            <View style={styles.NextButtonView}>
                            <TouchableOpacity 
                            onPress={() => {
                                this.doUserFavouriteUpdate();
                                borderColor="transparent"
                              }}>
                            <LinearGradient start={{x: 0, y: 0}} end={{x: 1, y: 0}} colors={['#3e3d3d', '#1f1a1a']} style={styles.NextButton}>
                            
                            
                            {/* <Image style={styles.ista_logo} 
                                    source={require('../images/instagram_logo.png')}> 
                                </Image> */}
                                    <Text style={styles.buttonText}>
                                    NEXT
                                    </Text>
                                   
                                
                                </LinearGradient>
                                </TouchableOpacity>
                            </View>
                            
                {/* end */}
      </View>
      {
						this.state.isLoading &&
						<Spinner />
          }
      {/* <View style={styles.fourthrow}></View> */}
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
    
        flex: 1.8,
        flexDirection: isRTL ? 'row-reverse' : 'row',
        //backgroundColor: "#ffc857",
        borderBottomWidth:0.2,
        borderColor: "#727171",
        marginTop: 20,
        marginHorizontal: 13
    
      },
    
      secondrow: {
        flex: 6.4,
        //backgroundColor: "#4b3f72"
        borderBottomWidth:0.2,
        borderColor: "#727171",
        marginHorizontal: 13
      },
    
      thirdrow: {
        flex: 1.6,
        // backgroundColor: "#119da4"
        // borderBottomWidth:1,
        borderColor: "#fff",
        marginHorizontal: 20
      },
    
    //   fourthrow: {
    //     flex: 3,
    //     // backgroundColor: "#19647e"
    //     borderBottomWidth:1,
    //     borderColor: "#fff"
    //   },
    
  headerIcon: {
    textAlign : isRTL ? "left" : "right",
    // marginLeft: 15,
    width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 30,
    height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 30 : 40 : 30,
    borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
    marginTop: 40
},
headerIconRight: {
  textAlign : isRTL ? "left" : "right",
    width: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 28,
    height: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 25 : 40 : 28,
    //borderRadius: (Platform.OS === 'ios') ? (aspectRatio > 1.6) ? 15 : 20 : 30,
    marginTop: 40,
    // zIndex:1
},
profileImage: {
  flex: 0.3,
  justifyContent: 'center',
    alignItems: isRTL ? 'flex-start' : 'flex-end',
    margin: 5,
    paddingTop: ( Platform.OS === 'ios' ) ? 20 : 0,
    marginLeft:10
},
infoContainer: {
flex:0.7,
justifyContent: 'center',
alignItems: isRTL ? 'flex-end' : 'flex-start',
margin: 5,
paddingTop: ( Platform.OS === 'ios' ) ? 5 : 0
},
userFullName: {
  color: "#fff",
  fontSize: 18
},
followerCount: {
  color: "#fff",
  fontSize: 13
},
  GridContainer: {
    flex: 1,
    marginVertical: 10, 
    // marginHorizontal: 10,
  },
  item: {
    backgroundColor: 'transparent',//'#4D243D',
    alignItems: 'center',
    justifyContent: 'center',
    flex: 1,
    margin: 15,
    height: Dimensions.get('window').width / numColumns, // approximate a square
  },
  itemInvisible: {
    backgroundColor: 'transparent',
  },
  itemText: {
    marginVertical: 10,
    color: '#fff',
  },

//   title: {
//     fontSize: 20,
//     color: "#fff",
//     textAlign: "center",
//     marginBottom: 10
//   },
  
//   loader: {
//     flex: 1, 
//     justifyContent: "center",
//     alignItems: "center",
//     backgroundColor: "#fff"
//   },
  
  list: { 
    // paddingVertical: 5,
    // margin: 3,
    // flexDirection: "row",
    // backgroundColor: "#192338",
    // justifyContent: "flex-start",
    alignItems: "center",
    zIndex: -1,
    // marginBottom: 30
  },
  NextButtonView: {
    flex:1,
    marginTop:30,
    marginHorizontal: 100,
    marginBottom: 40
},
  NextButton: {
    //backgroundColor: 'transparent',
    paddingVertical: 10, 
    borderRadius:100,
    borderWidth: 1,
    borderColor: 'transparent',
    flexDirection:'row',
    alignItems: 'center',
     
    justifyContent:'center'

  },
  buttonText: {
    textAlign: 'center',
    color :'#ffffff',
    fontWeight: 'bold',
    fontSize: 18,
    paddingHorizontal:10,
    // fontFamily: "Billabong",
},
  
//   lightText: {
//     color: "#f7f7f7",
//     width: 200,
//     paddingLeft: 15,
//     fontSize: 12
//    },
  
//   line: {
//     height: 0.5,
//     width: "100%",
//     backgroundColor:"rgba(255,255,255,0.5)"
//   },
  
//   icon: {
//     position: "absolute",  
//     bottom: 20,
//     width: "100%", 
//     left: 290, 
//     zIndex: 1
//   },
  
//   numberBox: {
//     position: "absolute",
//     bottom: 75,
//     width: 30,
//     height: 30,
//     borderRadius: 15,  
//     left: 330,
//     zIndex: 3,
//     backgroundColor: "#e3e3e3",
//     justifyContent: "center",
//     alignItems: "center"
//   },
  
//   number: {fontSize: 14,color: "#000"},
  
  selected: {
      //backgroundColor: "rgb(100, 100, 100)",
    // borderColor: '#2196F3',
    // marginTop:7,
    //   marginBottom:7,
    //   opacity:1.5
    // ...Platform.select({
    //     ios: { 
    //       shadowColor: '#7CFC00',
    //       shadowOffset: { width: 0, height: 2 },
    //       shadowOpacity: 0.8,
    //       shadowRadius: 2,    
    //     },
    //     android: {
    //     //   elevation: 5,
    //     shadowColor: '#7CFC00',
    //       shadowOffset: { width: 0, height: 2 },
    //       shadowOpacity: 0.8,
    //       shadowRadius: 2, 
    //     },
    //   }),
    },
    elevationLow: {
        ...Platform.select({
          ios: {
            shadowColor: '#000',
            shadowOffset: { width: 0, height: 2 },
            shadowOpacity: 0.8,
            shadowRadius: 2,    
          },
          android: {
            elevation: 5,
          },
        }),
      },
    headerView: {
        justifyContent: "center",
        flexDirection: "row",
        alignItems: "center",
        ...ifIphoneX(
          {
            height: 65,
            marginTop: 5
          },
          {
            height: 30,
            marginTop: 5
          }
        ),
        backgroundColor: "transparent",
        elevation: 7,
        shadowOpacity: 7
      },
});