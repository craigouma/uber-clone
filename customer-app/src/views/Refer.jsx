import React  from "react";
import {
  StyleSheet,
  View
} from "react-native";
import { useNavigation } from "@react-navigation/native";
import { screenHeight, screenWidth } from '../config/Constants';

const Refer = (props) => {
  const navigation = useNavigation();

  return (
    <View style={styles.container}>
      
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    ...StyleSheet.absoluteFillObject,
    height: screenHeight,
    width: screenWidth,
  },
 });

export default Refer;