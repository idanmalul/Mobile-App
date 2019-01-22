import React, { Component } from 'react'
import {
    View, Modal, StyleSheet,
    ActivityIndicator, Text
} from 'react-native'
import { Colors } from '../themes';

export default class Spinner extends Component {
    constructor(props) {
        super(props)
        this.state = {
            isLoading: true,
        }
    }

    render() {
        return (
            <View style={styles.container}>
                <Modal
                    transparent={true}
                    supportedOrientations={['portrait', 'landscape']}
                    visible={this.state.isLoading}
                    onRequestClose={() => {this.setState({ isLoading: false })}}>
                    <View style={styles.mainView}>
                        <View style={styles.childView}>
                            <ActivityIndicator
                                style={styles.ActivityIndicator}
                                color={Colors.orangeColor}
                                size='large'
                                animating={this.state.isLoading}
                            />
                            <Text style={styles.loadingText}>Please Wait...</Text>
                        </View>
                    </View>
                </Modal>
            </View>
        )
    }
}
const styles = StyleSheet.create({
    container: {
        flex: 1
    },
    mainView: {
        flex: 1,
        justifyContent: 'center',
        backgroundColor: '#00000040'
    },
    childView: {
        height: 70,
        marginLeft: 20,
        marginRight: 20,
        alignItems: 'center',
        backgroundColor: "#FFF",
        flexDirection: 'row',
        borderRadius: 3
    },
    ActivityIndicator: {
        marginLeft: 15
    },
    loadingText: {
        marginLeft: 20,
        fontSize: 16,
        color: 'gray',
        fontWeight: 'bold'
    }
});
