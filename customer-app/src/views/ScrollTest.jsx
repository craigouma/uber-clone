import { FlatList, StyleSheet, View, ViewToken } from 'react-native';
import React from 'react';
//import { useSharedValue } from 'react-native-reanimated';
//import { ListItem } from '../components/ListItem';
import Animated, {useAnimatedStyle, withTiming, useSharedValue } from 'react-native-reanimated';
const data = new Array(20).fill(0).map((_, index) => ({ id: index }));
// [{id: 0}, {id: 1}, {id: 2}, ..., {id: 49}]

export default function App() {
  const viewableItems = useSharedValue([]);

  type ListItemProps = {
    viewableItems: Animated.SharedValue<ViewToken[]>;
    item: {
      id: number;
    };
  };

  const ListItem: React.FC<ListItemProps> = React.memo(
    ({ item, viewableItems }) => {
      const rStyle = useAnimatedStyle(() => {
        const isVisible = Boolean(
          viewableItems.value
            .filter((item) => item.isViewable)
            .find((viewableItem) => viewableItem.item.id === item.id)
        );
        return {
          opacity: withTiming(isVisible ? 1 : 0),
          transform: [
            {
              scale: withTiming(isVisible ? 1 : 0.6),
            },
          ],
        };
      }, []);
      return (
        <Animated.View
          style={[
            {
              height: 80,
              width: '90%',
              backgroundColor: '#78CAD2',
              alignSelf: 'center',
              borderRadius: 15,
              marginTop: 20,
            },
            rStyle,
          ]}
        />
      );
    }
  );

  return (
    <View style={styles.container}>
      <FlatList
        data={data}
        contentContainerStyle={{ paddingTop: 40 }}
        onViewableItemsChanged={({ viewableItems: vItems }) => {
          viewableItems.value = vItems;
        }}
        renderItem={({ item }) => {
          return <ListItem item={item} viewableItems={viewableItems} />;
        }}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
});