const characterIsReadyToFight = (unixTimestampFirstAvailable: number): boolean => {
    const currentTimestamp = Date.now();

    return currentTimestamp > 1000 * unixTimestampFirstAvailable;
};

export default characterIsReadyToFight;
