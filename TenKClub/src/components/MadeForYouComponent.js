import React, {Component} from 'react'
import {Text,
        View, 
        ScrollView,
        FlatList, 
        VirtualizedList, 
        TouchableHighlight, 
        StyleSheet,
        Image,
        TouchableWithoutFeedback,
        TouchableOpacity,
        Dimensions,
        AsyncStorage
} from 'react-native';
import {withNavigation} from "react-navigation";
import { madeForUList } from "../constants/apis";
import Spinner from "../components/Spinner";

// import {connect} from 'react-redux'
// import {fetchData} from '../actions'
// import Header from './Header'
// import Slide from './Slide'
// var width = Dimensions.get('window').width; //full width
// var height = Dimensions.get('window').height; //full height
const { height, width } = Dimensions.get('window');
const aspectRatio = height / width;

let shows_first = [];

class MadeForYouComponent extends Component {
    constructor(props){
        super(props)
        this.state = {
            // showPopup: false,
            isLoading: true,
            // profileData: []
          };
          // alert("unmount");
    }

    getMadeForYouOffers = ()=>{
       
        // multipleData = [];
    AsyncStorage.multiGet(['username', 'password', 'user_id']).then((data) => {
          let user_id = data[2][1];
         // let password = data[1][1];
        //   username = 'mohan.das.99990';
          let formBody = "user_id="+user_id;

          // setTimeout(() => {
          //   this.setState({ isLoading: false });
          // }, 10000);
          if (user_id !== null && user_id !== ''){
            
          fetch(
                // 'http://192.168.1.23/10k-club/webservices/get_user_profile.php',
                // 'http://10k.tempurl.co.il/webservices/get_user_profile.php',
                madeForUList,
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
            // alert(JSON.stringify(res));
            console.log("Made for you response => ");
            console.log(res);
            // return false;
          if(res.status === 'true'){
          shows_first = res.response;
        //   shows_second = res.response;
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
            
            // this.setState({ 
            //   userData: res.user_details,
            //   FavouritesData: responseJson
            // });
            this.setState({ isLoading: false });
            // this._singleTapMultipleSelectedButtons(res.user_details.favourites);
            //this.setState.multipleSelectedData.includes(res.user_details.favourites);
          }
          else {
            this.setState({ isLoading: false });
            // alert('test test not work',res.message);
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

        componentWillMount() {
            //alert(isRTL);
            this.getMadeForYouOffers();
            
          }
          componentWillUnmount() {
            // alert("unmount");
            // this.getMadeForYouOffers();
            
          }
          

    _renderItemApprovedUsers(item,index){
        // alert(item.key);
        return (
            <View style={{flex:1}}>
            { 
                index==0 &&
                <View style={{alignItems: 'flex-start',marginLeft:0}}>
                <Image style={{width: 30, height: 30, borderRadius:30/2, zIndex:9999, marginTop:0, position:'absolute', left:0}} source={{uri: item.profile_pic_url}}  />
                {/* <View>
                <Text style={{fontSize:9,color:"#fff"}}>{item.approved_users_count}</Text>
                </View> */}
                
                </View>
            }

            { 
                index==1 &&
                <View style={{alignItems: 'flex-start'}}>
                <Image style={{width: 30, height: 30, borderRadius:30/2, zIndex:9999, marginTop:0, position:'absolute', left:18}} source={{uri: item.profile_pic_url}}  />
                {/* <View>
                <Text style={{fontSize:9,color:"#fff"}}>{item.approved_users_count}</Text>
                </View> */}
                </View>
            }

            { 
                index==2 &&
                <View style={{alignItems: 'flex-start'}}>
                <Image style={{width: 30, height: 30, borderRadius:30/2, zIndex:9999, marginTop:0, position:'absolute', left:36}} source={{uri: item.profile_pic_url}}  />
                {/* <View>
                <Text style={{fontSize:9,color:"#fff"}}>{item.approved_users_count}</Text>
                </View> */}
                </View>
            }
                
            
            </View>
                
                
             
        )
    }
    _renderItemMissedOffer(data){
        return (
            // <TouchableWithoutFeedback > 
            <View style={{flex:1,flexDirection:'row',marginBottom:35}}>
            
            {/* <View style={{flexDirection:"row",alignItems:"flex-start",justifyContent:"flex-start"}}> */}
            <View style={{flex:0.6}}>
            <Image style={{width: 130, height: 130, borderRadius: 130/2}} source={{uri: data.story_image}} resizeMode='cover'  />
            </View>
                
                {/* </View> */}
                <View style={{flex:0.8,marginTop:0}}>
                <View style={{borderBottomWidth:0.5,borderColor:"#727171"}}>
                <Text style={{fontSize:25,color:"#fff", textAlign:'left'}}>{data.story_title}</Text>
                </View>
                <View>
                <Text style={{color:"#fff", textAlign:'left',marginTop:5}}>{data.short_description}</Text>
                </View>
                <View style={styles.ButtonContainer}>
                          
                            
                            <TouchableOpacity onPress={() => this.props.navigation.navigate('OfferDetails', {
                                                item: data,
                                                callHome: this.homeFun.bind(this)
                                            })} style={styles.Button}
                            
                            >
                                    <Text style={styles.buttonText}>
                                    SEE MORE
                                    </Text>
                                </TouchableOpacity>
                                
                            </View>
                <View style={{flex:1,flexDirection:'row',textAlign:'left',marginTop:3}}>
                <FlatList
                contentContainerStyle={styles.list}
                            // horizontal={true}
                            data={data.approved_users}
                            // SeparatorComponent={() => <View  style={{width: 5}}/>}
                            renderItem={({item,index}) => this._renderItemApprovedUsers(item,index)}
                            keyExtractor={ (item, index) => index.toString() }
                        />

                <View style={{alignItems:"flex-start"}}>
                <Text style={{fontSize:9,color:"#fff",marginLeft:70,marginTop:15,justifyContent:"flex-end"}}>{ (data.approved_users.length !== 0) ? 'friends joined the offer '+data.approved_users[0].total_approved_count : ''}</Text>
                </View>
                
                </View>
                </View>
                
            </View>
            
            // </TouchableWithoutFeedback>
             
        )
    }
    // _renderItemAvailabeSoon(item){
    //     return (
    //         <View style={{marginRight:10}}>
            
    //             <Image style={{width: 130, height: 100}} resizeMode="cover" source={{uri: item.image}} />
    //             <Text style={{color:"#fff", textAlign:'center',marginTop:5,width: 130}}>{item.name}</Text>
    //             {/* <Text style={{color:"#fff", textAlign:'center',marginTop:5}}>{item.description}</Text> */}
                
    //         </View>
             
    //     )
    // }
    homeFun(){
      // alert("home");
      this.getMadeForYouOffers();
    }
    render(){
      // alert("yes");
      // this.getMadeForYouOffers();
        return (
            <View style={{flex: 1, backgroundColor:'transparent'}}>
                {/* <Header /> */}
                <ScrollView>
                {/* <Slide /> */}
                <View style={{marginLeft: 0}}>
                    <View style={{flex:1,marginTop: 10,borderBottomWidth:0,borderColor:"#727171",marginHorizontal:15}}>
                    <View style={{flex:1,flexDirection:"row",paddingBottom:10}}>
                            <Text style={{color: 'white', fontSize: 15}}>MADE FOR YOU</Text>
                        </View>
                        <FlatList
                        // style={{ flex: 1}}
                        // contentContainerStyle={{flexDirection:"row",textAlign:'left'}}
                        // initialNumToRender={3}
                            // showsHorizontalScrollIndicator={false}
                            // horizontal
                            data={shows_first}
                            SeparatorComponent={() => <View  style={{width: 0}}/>}
                            renderItem={({item}) => this._renderItemMissedOffer(item)}
                            keyExtractor={ (item, index) => index.toString() }
                        />
                        <View style={{marginTop:20}}></View>
                    </View>
                    
                    {/* <View style={{marginTop: 10}}>
                        <View style={{flex:1,flexDirection:"row",marginHorizontal:15, justifyContent:"center",alignItems:"center",paddingBottom:10}}>
                        <Text style={{color: 'white', fontSize: 15}}>AVAILABLE SOON</Text>
                       <Text style={{color: 'white', fontSize: 8,fontStyle: 'italic'}}>  Improved by rating level</Text>
                        <Text style={{color: '#3A829B', fontSize: 10}}> 12,568 Points</Text>
                        <Text style={{color: 'white', fontSize: 10,fontStyle: 'italic'}}> For next level</Text>
                        </View>  
                        <FlatList
                            showsHorizontalScrollIndicator={false}
                            horizontal
                            data={shows_second}
                            SeparatorComponent={() => <View  style={{width: 10}}/>}
                            renderItem={({item}) => this._renderItemAvailabeSoon(item)}
                            keyExtractor={ (item, index) => index.toString() }
                        />
                    </View> */}
                </View>
                </ScrollView>
            </View>
        )
    }
}

export default withNavigation(MadeForYouComponent);

const styles = StyleSheet.create({
  container: {
    flex: 1,
    marginTop: 100
  },
//   text: {
//     textAlign: 'center'
//   },
//   button: {
//     height: 60,
//     margin: 10,
//     justifyContent: 'center',
//     alignItems: 'center',
//     backgroundColor: '#0b7eff'
//   },
//   buttonText: {
//     color: 'white'
//   },
  list: {
    // flex:1,
    alignContent:'space-between',
    flexDirection: "row",
    // flexWrap: "wrap"
    
    },
    ButtonContainer:{
        position: 'relative',
        paddingVertical: 10,
        paddingHorizontal: 45,    
      },
      Button: {
        backgroundColor: '#235578',
        paddingVertical: 3,
        borderRadius:100
      },
      buttonText: {
        textAlign: 'center',
        color :'#ffffff',
        // fontWeight: 'bold',
        fontSize: 12
    },
})

// const mapStateToProps = state => {
//     return {
//         source: state.dataReducers
//     }
// }
// const mapDispatchToProps = dispatch => {
//     return {
//         fetchData: () => dispatch(fetchData())
//     }
// }

// export default connect(mapStateToProps, mapDispatchToProps)(FlatListComponent)