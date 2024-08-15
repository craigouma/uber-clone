import React, { useEffect, useState } from "react";
import {
  StyleSheet,
  View,
  Text,
  StatusBar
} from "react-native";
import { useNavigation, CommonActions } from "@react-navigation/native";
import { connect } from 'react-redux';
import { f_s, regular } from '../config/Constants';
import AsyncStorage from '@react-native-async-storage/async-storage';
import * as colors from '../assets/css/Colors';

const Logout = (props) => {
  const navigation = useNavigation();
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    AsyncStorage.clear();
    navigate();
  }, []);

  const navigate = () => {
    navigation.dispatch(
      CommonActions.reset({
        index: 0,
        routes: [{ name: "CheckPhone" }],
      })
    );
  }

  return (
    <View style={{ alignItems: 'center', justifyContent: 'center', flex: 1 }}>
      <StatusBar
        backgroundColor={colors.theme_bg}
      />
      <Text style={{ color: colors.theme_fg_two, fontSize: f_s, fontFamily: regular }}>Loading...</Text>
    </View>
  );
};

const styles = StyleSheet.create({
  logo_container: {
    flex: 1
  }
});

export default connect(null, null)(Logout);